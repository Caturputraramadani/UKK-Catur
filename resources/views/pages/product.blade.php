@extends('layouts.main')
@section('container')
    <div class="flex items-center text-gray-500 text-sm ml-2">
        <i class="ti ti-home text-xl"></i>
        <i class="ti ti-chevron-right text-xl mx-2"></i>
        <span class="text-gray-500 font-semibold text-lg">Product</span>
    </div>

    <h1 class="text-gray-700 text-2xl font-bold ml-2">PRODUCT</h1>
    <div class="card">
        <div class="card-body">

            @if (Auth::check() && Auth::user()->role === 'admin')
            <div class="flex justify-between items-center mb-4">
                <a href="{{ route('products.export') }}" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-full mb-3">
                    Export Product (.xlsx)
                </a>
                <a href="javascript:;" class="btn btn-primary waves-effect btn-label waves-light"
                onclick="openProductModal()">Add Product</a>
            </div>
            @endif

            <div class="relative overflow-x-auto">
                <table class="text-left w-full whitespace-nowrap text-sm text-gray-500">
                    <thead>
                        <tr class="text-sm">
                            <th class="p-4 font-semibold">#</th>
                            <th class="p-4 font-semibold">IMAGE</th>
                            <th class="p-4 font-semibold">PRODUCT NAME</th>
                            <th class="p-4 font-semibold">PRICE</th>
                            <th class="p-4 font-semibold">STOCK</th>
                            @if (Auth::check() && Auth::user()->role === 'admin')
                            <th class="p-4 font-semibold">ACTION</th>
                            @endif
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($products as $product)
                            <tr>
                                <td class="p-4">
                                    <h3 class="font-medium">{{ $loop->iteration }}</h3>
                                </td>
                                <td class="p-4">
                                    @if ($product->images)
                                        <img src="{{ asset('storage/' . $product->images) }}"
                                            class="w-32 h-32 object-cover rounded-lg">
                                    @else
                                        <span class="text-gray-500">No Image</span>
                                    @endif
                                </td>
                                <td class="p-4">
                                    <h3 class="font-medium">{{ $product->name }}</h3>
                                </td>
                                <td class="p-4">
                                    <h3 class="font-medium">Rp. {{ number_format($product->price) }}</h3>
                                </td>
                                <td class="p-4">
                                    <h3 class="font-medium text-teal-500">{{ $product->stock }}</h3>
                                </td>
                                @if (Auth::check() && Auth::user()->role === 'admin')
                                <td class="p-4">
                                    <div
                                        class="hs-dropdown relative inline-flex [--placement:bottom-right] sm:[--trigger:hover]">
                                        <a class="relative hs-dropdown-toggle cursor-pointer align-middle rounded-full">
                                            <i class="ti ti-dots-vertical text-2xl text-gray-400"></i>
                                        </a>
                                        <div
                                            class="card hs-dropdown-menu transition-[opacity,margin] rounded-md duration hs-dropdown-open:opacity-100 opacity-0 mt-2 min-w-max w-[150px] hidden z-[12]">
                                            <div class="card-body p-0 py-2">
                                                <a href="javascript:void(0)"
                                                    onclick="openProductModal({{ json_encode($product) }})"
                                                    class="flex gap-2 items-center font-medium px-4 py-2.5 hover:bg-gray-200 text-gray-400">
                                                    <p class="text-sm">Edit</p>
                                                </a>
                                                <a href="javascript:void(0)"
                                                    onclick="openStockModal({{ json_encode($product) }})"
                                                    class="flex gap-2 items-center font-medium px-4 py-2.5 hover:bg-gray-200 text-gray-400">
                                                    <p class="text-sm">Update Stock</p>
                                                </a>


                                                <a href="javascript:void(0)" onclick="deleteProduct('{{ $product->id }}')"
                                                    class="flex gap-2 items-center font-medium px-4 py-2.5 hover:bg-gray-200 text-gray-400">
                                                    <p class="text-sm">Delete</p>
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                </td>
                                @endif

                            </tr>
                        @empty
                            <tr>
                                <td colspan="4" class="p-4 text-center text-gray-500">No products available</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

    <!-- Modal Add/Edit Data -->
    <div id="dataModal" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50 hidden z-[999]">
        <div class="bg-white rounded-lg p-6 w-full sm:w-96 max-w-md">
            <div class="flex justify-between items-center">
                <h3 id="modalTitle" class="text-lg font-semibold text-gray-900">Add Product</h3>
                <button type="button" onclick="closeProductModal()"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <form id="productForm" action="{{ route('products.save') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="mt-4">
                    <label for="name" class="block text-sm font-medium text-gray-900">Product Name</label>
                    <input type="text" id="name" name="name"
                        class="mt-1 p-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>
                <div class="mt-4">
                    <label for="price" class="block text-sm font-medium text-gray-900">Price</label>
                    <input type="text" id="price" name="price"
                    class="form-input mt-1 p-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                    oninput="formatRupiah(this)" required>

                </div>
                <div class="mt-4">
                    <label for="stock" class="block text-sm font-medium text-gray-900">Stock</label>
                    <input type="number" id="stock" name="stock"
                        class="mt-1 p-2 w-full border border-gray-300 rounded-lg bg-gray-100 focus:bg-blue-100 focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>

                <div class="mt-4">
                    <label for="image" class="block text-sm font-medium text-gray-900">Image</label>
                    <input type="file" id="images" name="images"
                        class="mt-1 p-2 w-full border border-gray-300 rounded-lg" onchange="previewImage(event)">
                </div>

                <!-- Image Preview -->
                <div id="imagePreviewContainer" class="mt-4 hidden">
                    <img id="imagePreview" src="#" alt="Image Preview"
                        class="w-32 h-32 object-cover rounded-lg" />
                </div>

                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeProductModal()"
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Modal Update Stock -->
    <div id="stockModal" class="fixed inset-0 flex items-center justify-center bg-gray-500 bg-opacity-50 hidden z-[999]">
        <div class="bg-white rounded-lg p-6 w-full sm:w-96 max-w-md">
            <div class="flex justify-between items-center">
                <h3 id="stockModalTitle" class="text-lg font-semibold text-gray-900">Update Stock</h3>
                <button type="button" onclick="closeStockModal()"
                    class="text-gray-400 bg-transparent hover:bg-gray-200 hover:text-gray-900 rounded-lg text-sm w-8 h-8 inline-flex justify-center items-center">
                    <svg class="w-3 h-3" aria-hidden="true" xmlns="http://www.w3.org/2000/svg" fill="none"
                        viewBox="0 0 14 14">
                        <path stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="m1 1 6 6m0 0 6 6M7 7l6-6M7 7l-6 6" />
                    </svg>
                    <span class="sr-only">Close modal</span>
                </button>
            </div>
            <form id="stockForm" action="" method="POST">
                @csrf
                @method('PUT')
                <div class="mt-4">
                    <label for="productName" class="block text-sm font-medium text-gray-900">Product Name</label>
                    <input type="text" id="productName" name="productName"
                        class="mt-1 p-2 w-full border border-gray-300   -lg bg-gray-100 focus:bg-gray-100 focus:ring-0 focus:border-gray-300"
                        readonly>
                </div>
                <div class="mt-4">
                    <label for="updateStock" class="block text-sm font-medium text-gray-900">Stock</label>
                    <input type="number" id="updateStock" name="stock"
                        class="mt-1 p-2 w-full border border-gray-300 rounded-lg focus:ring-blue-500 focus:border-blue-500"
                        required>
                </div>
                <div class="mt-6 flex justify-end gap-3">
                    <button type="button" onclick="closeStockModal()"
                        class="px-4 py-2 bg-gray-500 text-white rounded-lg">Cancel</button>
                    <button type="submit" class="px-4 py-2 bg-blue-500 text-white rounded-lg">Save</button>
                </div>
            </form>
        </div>
    </div>
@endsection

