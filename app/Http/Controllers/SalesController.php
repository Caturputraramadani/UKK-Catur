<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use App\Models\SaleDetail;
use App\Models\Product;
use App\Models\Member;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

use Barryvdh\DomPDF\Facade\Pdf;

use App\Exports\SalesExport;
use Maatwebsite\Excel\Facades\Excel;


class SalesController extends Controller
{

    public function index(Request $request)
    {
        $perPage = $request->input('per_page', 10);
        $search = $request->input('search');

        $query = Sale::with(['user', 'member'])->latest();

        if ($search) {
            $query->where(function($q) use ($search) {
                $q->whereHas('member', function($q2) use ($search) {
                    $q2->where('name', 'like', '%'.$search.'%');
                })
                ->orWhereHas('user', function($q2) use ($search) {
                    $q2->where('name', 'like', '%'.$search.'%');
                })
                ->orWhere('date', 'like', '%'.$search.'%')
                ->orWhere('sub_total', 'like', '%'.$search.'%')
                ->orWhereNull('member_id');
            });
        }

        $sales = $query->paginate($perPage)->onEachSide(1);

        if ($request->ajax()) {
            return response()->json([
                'html' => view('sales.partials.table', compact('sales'))->render(),
                'pagination' => view('sales.partials.pagination', [
                    'paginator' => $sales,
                    'elements' => $sales->links()->elements,
                    'entries_info' => "Showing {$sales->firstItem()} to {$sales->lastItem()} of {$sales->total()} entries"
                ])->render(),
            ]);
        }

        return view('sales.index', compact('sales'));
    }

    public function create()
    {
        $products = Product::all();
        return view('sales.create', compact('products'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'products' => 'required|array',
            'products.*.id' => 'required|exists:products,id',
            'products.*.quantity' => 'required|integer|min:1',
            'member_phone' => 'nullable|string',
            'amount_paid' => 'required|numeric|min:0',
            'point_used' => 'nullable|integer|min:0'
        ]);

        $subTotal = 0;
        $productsData = [];

        foreach ($request->products as $product) {
            $productModel = Product::find($product['id']);
            $totalPrice = $productModel->price * $product['quantity'];
            $subTotal += $totalPrice;

            $productsData[] = [
                'product_id' => $product['id'],
                'quantity_product' => $product['quantity'],
                'total_price' => $totalPrice
            ];
        }

        $member = null;
        if ($request->member_phone) {
            $member = Member::where('no_telephone', $request->member_phone)->first();
        }

        $change = $request->amount_paid - ($subTotal - ($request->point_used ?? 0));

        $sale = Sale::create([
            'date' => Carbon::now(),
            'user_id' => Auth::id(),
            'member_id' => $member ? $member->id : null,
            'point_used' => $request->point_used ?? 0,
            'change' => $change,
            'amount_paid' => $request->amount_paid,
            'sub_total' => $subTotal
        ]);


        foreach ($productsData as $productData) {
            $productData['sale_id'] = $sale->id;

            $product = Product::find($productData['product_id']);
            $product->stock -= $productData['quantity_product'];
            $product->save();
        }

        if ($member) {
            $pointsEarned = floor($subTotal / 10000);
            $member->point += $pointsEarned;
            $member->save();
        }

        return redirect()->route('sales.index', $sale->id);
    }


    public function destroy(Sale $sale)
    {
        foreach ($sale->saleDetails as $detail) {
            $product = Product::find($detail->product_id);
            $product->stock += $detail->quantity_product;
            $product->save();
        }

        $sale->delete();

        return redirect()->route('sales.index')->with('success', 'Sale deleted successfully');
    }


    public function postCreate(Request $request)
    {
        $productsInput = $request->input('products');

        if (is_string($productsInput)) {
            $selectedProducts = json_decode($productsInput, true);
        } else {
            $selectedProducts = $productsInput;
        }

        if (!$selectedProducts || !is_array($selectedProducts)) {
            return redirect()->back()->with('error', 'Produk tidak valid atau belum dipilih.');
        }

        session()->put('selectedProducts', $selectedProducts);

        $products = [];
        $total = 0;

        foreach ($selectedProducts as $item) {
            $productModel = Product::find($item['id']);
            if (!$productModel) {
                return redirect()->back()->with('error', 'Produk tidak ditemukan.');
            }

            $totalPrice = $productModel->price * $item['quantity'];
            $total += $totalPrice;

            $products[] = [
                'id' => $productModel->id,
                'name' => $productModel->name,
                'price' => $productModel->price,
                'quantity' => $item['quantity'],
                'subtotal' => $totalPrice,
            ];
        }

        return view('sales.post-create', compact('products', 'total'));
    }


    public function processPayment(Request $request)
{
    $request->validate([
        'products' => 'required|array',
        'products.*.id' => 'required|exists:products,id',
        'products.*.quantity' => 'required|integer|min:1',
        'amount_paid' => 'required|numeric|min:0',
        'member_phone' => 'nullable|string'
    ]);

    $subTotal = 0;
    $productsData = [];

    foreach ($request->products as $product) {
        $productModel = Product::find($product['id']);
        $totalPrice = $productModel->price * $product['quantity'];
        $subTotal += $totalPrice;

        $productsData[] = [
            'product_id' => $product['id'],
            'quantity_product' => $product['quantity'],
            'total_price' => $totalPrice
        ];
    }

    $member = null;
    $isNewMember = false;

    if ($request->member_phone) {
        $member = Member::where('no_telephone', $request->member_phone)->first();

        if (!$member) {
            $member = Member::create([
                'name' => '',
                'no_telephone' => $request->member_phone,
                'point' => 0,
                'point_history' => json_encode([]),
                'date' => Carbon::now()
            ]);
            $isNewMember = true;
        }
    }

    $change = $request->amount_paid - $subTotal;
    if ($change < 0) {
        return redirect()->back()->withErrors(['amount_paid' => 'Jumlah pembayaran kurang dari total harga.']);
    }

    $sale = Sale::create([
        'date' => Carbon::now(),
        'user_id' => Auth::id(),
        'member_id' => $member ? $member->id : null,
        'point_used' => 0,
        'change' => $change,
        'amount_paid' => $request->amount_paid,
        'sub_total' => $subTotal,
        'is_new_member' => $isNewMember
    ]);

    foreach ($productsData as $productData) {
        $sale->saleDetails()->create($productData);

        $product = Product::find($productData['product_id']);
        $product->stock -= $productData['quantity_product'];
        $product->save();
    }

    if ($member) {
        $earnedPoint = floor($subTotal * 0.01);
        
        
        $pointUsable = $member->point;
        
         $member->increment('point', $earnedPoint);
        $member->point_earned = $earnedPoint;
        $member->point_usable = $pointUsable;
        
       
        $pointHistory = json_decode($member->point_history, true) ?? [];
        $pointHistory[] = [
            'date' => Carbon::now()->toDateString(),
            'earned' => $earnedPoint,
            'usable' => $pointUsable,
            'type' => 'transaction'
        ];
        $member->point_history = json_encode($pointHistory);
        $member->save();
    }

    if ($member) {
        return redirect()->route('sales.memberPayment', ['id' => $sale->id]);
    }

    return redirect()->route('sales.detailPrint', ['id' => $sale->id]);
}


public function memberPayment($id)
{
    $sale = Sale::with(['saleDetails.product', 'member'])->findOrFail($id);
    $member = $sale->member;

    $isFirstPurchase = $member->sales()->count() === 1;
    $currentPoints = $member->point;
    $earnedPoints = $member->point_earned;
    $usablePoints = $member->point_usable;

    return view('sales.member-payment', [
        'sale' => $sale,
        'is_new_member' => $sale->is_new_member,
        'is_first_purchase' => $isFirstPurchase,
        'current_points' => $currentPoints,
        'earned_points' => $earnedPoints,
        'usable_points' => $usablePoints,
        'can_use_points' => !$isFirstPurchase && $usablePoints > 0
    ]);
}


public function updateMemberPayment(Request $request, $id)
{
    $sale = Sale::with('member')->findOrFail($id);
    $member = $sale->member;
    $isFirstPurchase = $member->sales()->count() === 1;

    $request->validate([
        'member_name' => 'required|string|max:255',
        'use_points' => 'nullable|boolean'
    ]);

    $member->update(['name' => $request->member_name]);

    $pointUsed = 0;
    $totalBefore = $sale->sub_total;

    if (!$isFirstPurchase && $request->use_points && $member->point_usable > 0) {
        $pointUsed = min($member->point_usable, $totalBefore);
        
        // Kurangi point yang bisa digunakan
        $member->decrement('point', $pointUsed);
        $member->point_usable -= $pointUsed;
        
        // Update history
        $pointHistory = json_decode($member->point_history, true) ?? [];
        $pointHistory[] = [
            'date' => Carbon::now()->toDateString(),
            'used' => $pointUsed,
            'remaining' => $member->point_usable,
            'type' => 'point_used'
        ];
        $member->point_history = json_encode($pointHistory);
        $member->save();
        
        $totalBefore -= $pointUsed;
    }

    $change = $sale->amount_paid - $totalBefore;

    $sale->update([
        'point_used' => $pointUsed,
        'sub_total' => $totalBefore,
        'change' => $change,
        'is_new_member' => false
    ]);

    return redirect()->route('sales.detailPrint', $sale->id);
}



    public function exportPdf($id)
    {
        $sale = Sale::with(['saleDetails.product', 'member', 'user'])->findOrFail($id);

        $data = [
            'sale' => $sale,
            'title' => 'Invoice #'.$sale->id
        ];

        $pdf = Pdf::loadView('sales.pdf-export', $data);
        return $pdf->download('invoice-'.$sale->id.'.pdf');
    }


    public function detail($id)
    {
        $sale = Sale::with(['saleDetails.product', 'member', 'user'])->findOrFail($id);

        $total = $sale->saleDetails->sum(function($detail) {
            return $detail->total_price;
        });

        return response()->json([
            'member' => $sale->member,
            'saleDetails' => $sale->saleDetails,
            'total' => $total,
            'sub_total' => $sale->sub_total,
            'created_at' => $sale->created_at->format('d M Y'),
            'created_by' => $sale->user->name
        ]);
    }


    public function exportExcel()
    {
        return Excel::download(new SalesExport, 'sales_export_' . date('Ymd_His') . '.xlsx');
    }


    public function getSalesChartData()
    {
        $endDate = Carbon::today();
        $startDate = Carbon::today()->startOfMonth();

        $salesData = Sale::selectRaw('DATE(date) as date, COUNT(*) as count')
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate)
            ->groupBy('date')
            ->orderBy('date')
            ->get();

        $result = [];
        $currentDate = $startDate->copy();

        while ($currentDate <= $endDate) {
            $dateString = $currentDate->format('Y-m-d');
            $found = $salesData->firstWhere('date', $dateString);

            $result[] = [
                'date' => $currentDate->format('d M'),
                'count' => $found ? $found->count : 0,
                'full_date' => $currentDate->format('d M Y')
            ];

            $currentDate->addDay();
        }

        return response()->json($result);
    }


    public function getProductSalesData()
    {
        try {
            $productSales = SaleDetail::with('product')
                ->selectRaw('product_id, SUM(quantity_product) as total_sold')
                ->groupBy('product_id')
                ->orderBy('total_sold', 'desc')
                ->get()
                ->map(function ($item) {
                    return [
                        'product_name' => $item->product->name ?? 'Produk Tidak Dikenal',
                        'total_sold' => (int)$item->total_sold
                    ];
                });

            if ($productSales->isEmpty()) {
                return response()->json([
                    'status' => 'success',
                    'message' => 'Tidak ada data penjualan',
                    'data' => []
                ], 200);
            }

            return response()->json([
                'status' => 'success',
                'data' => $productSales
            ]);

        } catch (\Exception $e) {
            return response()->json([
                'status' => 'error',
                'message' => 'Gagal memuat data: ' . $e->getMessage()
            ], 500);
        }
    }
}