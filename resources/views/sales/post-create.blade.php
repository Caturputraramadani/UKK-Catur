@extends('layouts.main')
@section('container')

    <div class="flex items-center text-gray-500 text-sm ml-2 mb-4">
        <i class="ti ti-home text-xl"></i>
        <i class="ti ti-chevron-right text-xl mx-2"></i>
        <span class="text-gray-500 font-semibold text-lg">Sales</span>
    </div>

    <h1 class="text-gray-700 text-2xl font-bold ml-2 mb-4">SALES</h1>

    <div class="card p-6">
        <div class="grid grid-cols-2 gap-8">
            <!-- Product Table (Left) -->
            <div>
                <h2 class="text-lg font-semibold mb-3">Selected Products</h2>
                <div class="bg-gray-100 p-4 rounded-lg">
                    <table class="w-full text-left whitespace-nowrap text-sm text-gray-500">
                        <tbody>
                            @foreach($products as $product)
                            <tr>
                                <td class="p-4 text-gray-700">{{ $product['name'] }}</td>
                                <td class="p-4 text-right text-gray-700">Rp {{ number_format($product['price'], 0, ',', '.') }} x {{ $product['quantity'] }}</td>
                                <td class="p-4 text-right font-semibold text-gray-900">Rp {{ number_format($product['subtotal'], 0, ',', '.') }}</td>
                            </tr>
                            @endforeach
                            <tr class="border-t">
                                <td colspan="2" class="p-4 text-right font-semibold text-lg text-gray-800">Total:</td>
                                <td class="p-4 text-right font-semibold text-lg text-gray-900">Rp {{ number_format($total, 0, ',', '.') }}</td>
                            </tr>
                        </tbody>
                    </table>
                </div>
            </div>

            <!-- Input Form (Right) -->
            <div>
                <form action="{{ route('sales.processPayment') }}" method="POST" id="paymentForm">
                    @csrf
                    <input type="hidden" name="total" value="{{ $total }}">

                    @foreach($products as $product)
                    <input type="hidden" name="products[{{ $loop->index }}][id]" value="{{ $product['id'] }}">
                    <input type="hidden" name="products[{{ $loop->index }}][quantity]" value="{{ $product['quantity'] }}">
                    <input type="hidden" name="products[{{ $loop->index }}][subtotal]" value="{{ $product['subtotal'] }}">
                    @endforeach

                    <!-- Member Status -->
                    <div class="mb-4 mt-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1">
                            Member Status <span class="text-red-500">You can also create a member</span>
                        </label>
                        <select name="is_member" id="memberSelect" class="w-full p-2 border border-gray-300 rounded-lg">
                            <option value="0" selected>Not a Member</option>
                            <option value="1">Member</option>
                        </select>
                    </div>

                    <!-- Phone Number (Only Numbers Allowed) -->
                    <div id="memberFields" class="hidden mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1">
                            Phone Number <span class="text-red-500">(register/use member)</span>
                        </label>
                        <input type="text" name="member_phone" id="member_phone"
                               class="w-full p-2 border border-gray-300 rounded-lg"
                               placeholder="Enter phone number"
                               oninput="this.value = this.value.replace(/[^0-9]/g, '')">
                    </div>

                    <div class="mb-4">
                        <label class="block text-gray-700 text-sm font-medium mb-1">Total Payment</label>
                        <div class="relative">
                            <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                            <input type="text" name="amount_paid" id="amount_paid"
                                   class="w-full p-2 pl-10 border border-gray-300 rounded-lg"
                                   placeholder="0"
                                   oninput="formatAndCheckPayment()" required>
                        </div>
                        <p id="paymentWarning" class="text-red-500 text-sm mt-1 hidden">Insufficient payment amount</p>
                    </div>

                    <!-- Order Button -->
                    <div class="text-right">
                        <button type="submit" class="bg-blue-500 text-white px-4 py-2 rounded-lg">Order</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

@endsection

