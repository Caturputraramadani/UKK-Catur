@extends('layouts.main')
@section('container')

<div class="flex items-center text-gray-500 text-sm ml-2">
    <i class="ti ti-home text-xl"></i>
    <i class="ti ti-chevron-right text-xl mx-2"></i>
    <span class="text-gray-500 font-semibold text-lg">Sales</span>
</div>

<h1 class="text-gray-700 text-2xl font-bold ml-2">SALES</h1>

<div class="bg-white shadow p-4 rounded-lg">
    <div class="grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 p-2">
        @foreach($products as $product)
        <div class="bg-gray-100 rounded-lg shadow-md p-4 flex flex-col items-center">
            <img src="{{ asset('storage/' . $product->images) }}" alt="{{ $product->name }}" class="h-40 w-full object-cover rounded-lg">
            <h5 class="mt-2 text-lg font-semibold text-gray-800 text-center">{{ $product->name }}</h5>
            <p class="text-sm text-gray-600 text-center">Stok: {{ $product->stock }}</p>
            <p class="text-md font-semibold text-gray-700 text-center">Rp. {{ number_format($product->price, 0, ',', '.') }}</p>

            <!-- Tombol Tambah & Kurang -->
            <div class="flex items-center mt-3">
                <button class="decrement bg-gray-400 text-white px-3 py-1 rounded-l hover:bg-gray-500 transition" data-id="{{ $product->id }}">-</button>
                <input class="quantity w-14 text-center border border-gray-300 py-1 outline-none" value="0" min="0" max="{{ $product->stock }}" data-price="{{ $product->price }}" data-id="{{ $product->id }}">
                <button class="increment bg-gray-400 text-white px-3 py-1 rounded-r hover:bg-gray-500 transition" data-id="{{ $product->id }}">+</button>
            </div>

            <p class="text-sm mt-2 font-semibold text-gray-700 text-center">Sub Total: <span class="subtotal" data-id="{{ $product->id }}">Rp. 0</span></p>
        </div>
        @endforeach
    </div>
</div>

<form id="productSelectionForm" method="POST" action="{{ route('sales.postCreate') }}">
    @csrf
    <!-- Hidden field untuk menyimpan produk yang dipilih -->
    <input type="hidden" name="products" id="selectedProductsInput">

    <!-- Tombol submit -->
    <div class="mt-4 text-right">
        <button type="submit" id="nextBtn" class="px-4 py-2 bg-blue-500 text-white rounded hover:bg-blue-600 disabled:opacity-50" disabled>
            Proceed to payment
        </button>
    </div>
</form>

@endsection
