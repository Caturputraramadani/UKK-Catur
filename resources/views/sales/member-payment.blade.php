@extends('layouts.main')
@section('container')

        <div class="flex items-center text-gray-500 text-sm ml-2">
            <i class="ti ti-home text-xl"></i>
            <i class="ti ti-chevron-right text-xl mx-2"></i>
            <span class="text-gray-500 font-semibold text-lg">Sales</span>
        </div>

        <h1 class="text-gray-700 text-2xl font-bold ml-2">SALES</h1>

        <div class="bg-white rounded-lg shadow-md p-6">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                <!-- Purchase Summary (Left) -->
                <div class="border-r md:pr-6">
                    <h3 class="text-lg font-semibold mb-2">Purchase Summary</h3>
                    <table class="w-full text-sm">
                        <thead>
                            <tr class="border-b">
                                <th class="text-left pb-2">Product Name</th>
                                <th class="text-center pb-2">Qty</th>
                                <th class="text-right pb-2">Price</th>
                                <th class="text-right pb-2">Subtotal</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($sale->saleDetails as $detail)
                            <tr class="border-b">
                                <td class="py-2">{{ $detail->product->name }}</td>
                                <td class="text-center py-2">{{ $detail->quantity_product }}</td>
                                <td class="text-right py-2">Rp {{ number_format($detail->product->price, 0, ',', '.') }}</td>
                                <td class="text-right py-2 font-semibold">Rp {{ number_format($detail->total_price, 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>

                    <div class="mt-4 space-y-2">
                        <div class="flex justify-between font-semibold">
                            <span>Total Price:</span>
                            <span>Rp {{ number_format($sale->sub_total, 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between font-semibold">
                            <span>Total Payment:</span>
                            <span>Rp {{ number_format($sale->amount_paid, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Member Information (Right) -->
                <div class="md:pl-6">
                    <h3 class="text-lg font-semibold mb-2">Member Information</h3>
                    <form action="{{ route('sales.updateMemberPayment', $sale->id) }}" method="POST" id="paymentForm">
                        @csrf

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-medium mb-1">Member Name (Identity)</label>
                            <input type="text" name="member_name" value="{{ $sale->member->name }}"
                                class="w-full p-2 border border-gray-300 rounded-lg"
                                {{ $sale->member->name ? 'readonly' : '' }} required>
                        </div>

                        <div class="mb-4">
                            <label class="block text-gray-700 text-sm font-medium mb-1">Points</label>
                            <input type="text" name="available_point" id="availablePoint"
                                class="w-full p-2 border border-gray-300 rounded-lg bg-gray-100"
                                value="{{ $sale->member->point }}" readonly>
                        </div>

                        @if($sale->member->point > 0)
                            <div class="mb-4">
                                <div class="flex items-center">
                                    <input type="checkbox" name="use_points" id="usePoints" class="mr-2" value="1"
                                    @if($is_first_purchase) disabled @endif>
                                    <label for="usePoints" class="text-sm">Use Points</label>
                                </div>
                            </div>
                        @endif

                        @if($is_first_purchase)
                            <div class="text-red-600 text-sm mb-4">
                                Points cannot be used on the first purchase!
                            </div>
                        @endif

                        <div class="text-right">
                            <button type="submit" class="bg-blue-500 hover:bg-blue-600 text-white px-4 py-2 rounded-lg transition duration-200">
                                Next
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>

@endsection
