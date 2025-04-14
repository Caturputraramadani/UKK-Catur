<?php

namespace App\Http\Controllers;

use App\Models\Sale;
use Illuminate\Http\Request;

class SaleDetailController extends Controller
{
    public function printDetail($id)
    {
        $sale = Sale::with(['saleDetails.product', 'user', 'member'])->findOrFail($id);
        return view('sales.detail-print', compact('sale'));
    }
}
