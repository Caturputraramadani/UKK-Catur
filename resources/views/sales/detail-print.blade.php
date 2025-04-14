@extends('layouts.main')
@section('container')

        <div class="flex items-center text-gray-500 text-sm ml-2">
            <i class="ti ti-home text-xl"></i>
            <i class="ti ti-chevron-right text-xl mx-2"></i>
            <span class="text-gray-500 font-semibold text-lg">Payment</span>
        </div>

        <h1 class="text-gray-700 text-2xl font-bold ml-2">PAYMENT</h1>

        <div class="bg-white shadow-md rounded-lg p-6">
            <!-- Download & Back Buttons -->
            <div class="flex justify-between items-center mb-4">
                <a href="{{ route('sales.exportPdf', $sale->id) }}" class="bg-blue-600 text-white px-4 py-2 rounded">Download PDF</a>
                <a href="{{ url('/sales') }}" class="bg-gray-600 text-white px-4 py-2 rounded">Back</a>
            </div>

            <!-- Member & Invoice Information -->
            <div class="flex justify-between pb-4 mb-4">
                @if($sale->member)
                <div>
                    <p class="font-bold text-lg">{{ $sale->member->no_telephone }}</p>
                    <p class="text-gray-600">MEMBER SINCE: {{ $sale->member->created_at->format('d F Y') }}</p>
                    <p class="text-gray-600">MEMBER POINTS: {{ $sale->member->point }}</p>
                </div>
                @endif
                <div class="text-right">
                    <p class="text-gray-600">Invoice - #{{ $sale->id }}</p>
                    <p class="text-gray-600">{{ \Carbon\Carbon::parse($sale->date)->format('d F Y') }}</p>
                </div>
            </div>

            <!-- Product Table -->
            <table class="w-full text-left border-t border-gray-300">
                <thead>
                    <tr class="text-gray-700">
                        <th class="py-2">Product</th>
                        <th class="py-2">Price</th>
                        <th class="py-2">Quantity</th>
                        <th class="py-2">Subtotal</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($sale->saleDetails as $detail)
                        <tr class="border-t border-gray-300">
                            <td class="py-2">{{ $detail->product->name }}</td>
                            <td class="py-2">Rp {{ number_format($detail->product->price, 0, ',', '.') }}</td>
                            <td class="py-2">{{ $detail->quantity_product }}</td>
                            <td class="py-2">Rp {{ number_format($detail->total_price, 0, ',', '.') }}</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>

            <!-- Additional Information -->
            <div class="mt-4 bg-gray-100 p-4 rounded-lg flex justify-between items-center">
                <div>
                    <p><strong>POINTS USED</strong></p>
                    <p class="text-lg">{{ $sale->point_used ?? 0 }}</p>
                </div>
                <div>
                    <p><strong>CASHIER</strong></p>
                    <p class="text-lg">{{ $sale->user->name }}</p>
                </div>
                <div>
                    <p><strong>CHANGE</strong></p>
                    <p class="text-lg font-bold text-gray-900">Rp {{ number_format($sale->change, 0, ',', '.') }}</p>
                </div>
                <div class="bg-gray-800 text-white p-4 rounded-lg">
                    <p class="text-sm">TOTAL</p>
                    @if($sale->point_used > 0)
                        <p class="text-xl font-bold line-through opacity-75">
                            Rp {{ number_format($sale->saleDetails->sum('total_price'), 0, ',', '.') }}
                        </p>
                        <p class="text-xl font-bold">
                            Rp {{ number_format($sale->sub_total, 0, ',', '.') }}
                        </p>
                    @else
                        <p class="text-xl font-bold">
                            Rp {{ number_format($sale->sub_total, 0, ',', '.') }}
                        </p>
                    @endif
                </div>
            </div>
        </div>

 @endsection
