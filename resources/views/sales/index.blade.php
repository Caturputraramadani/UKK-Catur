@extends('layouts.main')
@section('container')

    <div class="flex items-center text-gray-500 text-sm ml-2">
        <i class="ti ti-home text-xl"></i>
        <i class="ti ti-chevron-right text-xl mx-2"></i>
        <span class="text-gray-500 font-semibold text-lg">Sales</span>
    </div>

    <h1 class="text-gray-700 text-2xl font-bold ml-2">SALES</h1>
    <div class="card">
        <div class="card-body">
            <div class="flex justify-between mt-4 mb-4">
                <!-- Export Sales button on the left -->
                <a href="{{ route('sales.exportExcel') }}" class="bg-green-500 hover:bg-green-600 text-white py-2 px-4 rounded-full mb-3">
                    Export Sales (.xlsx)
                </a>

                <!-- Add Sales button on the right -->
                @if (Auth::check() && Auth::user()->role === 'employee')
                    <a href="{{ route('sales.create') }}" class="btn btn-primary">
                        <i class="fas fa-plus"></i> Add Sales
                    </a>
                @endif
            </div>

            <!-- Search and Pagination Controls -->
            <div class="flex justify-between items-center mb-4 flex-wrap gap-4">
                <div class="flex items-center gap-2">
                    <div class="relative">
                        <input type="text" id="salesSearchInput" placeholder="Search..."
                            value="{{ request('search') }}"
                            class="pl-10 pr-4 py-2 border border-gray-300 rounded-full shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                        <div class="absolute left-3 top-2.5">
                            <i class="fas fa-search text-gray-400"></i>
                        </div>
                    </div>
                </div>

                <div class="flex items-center text-sm gap-2">
                    <span class="text-gray-600">Show:</span>
                    <div class="relative">
                        <select id="salesPerPage"
                            class="appearance-none border border-gray-300 rounded-md px-3 pr-8 py-1.5 shadow-sm focus:ring-2 focus:ring-blue-500 focus:outline-none">
                            <option value="10" {{ request('per_page') == 10 ? 'selected' : '' }}>10</option>
                            <option value="25" {{ request('per_page') == 25 ? 'selected' : '' }}>25</option>
                            <option value="50" {{ request('per_page') == 50 ? 'selected' : '' }}>50</option>
                            <option value="100" {{ request('per_page') == 100 ? 'selected' : '' }}>100</option>
                        </select>
                    </div>
                    <span class="text-gray-600">entries</span>
                </div>
            </div>

            <div class="relative overflow-x-auto sales-container" id="salesTableContainer">
                @include('sales.partials.table', ['sales' => $sales])
            </div>

            <div class="mt-4" id="salesPaginationLinks">
                @if(isset($sales))
                    @include('sales.partials.pagination', [
                        'paginator' => $sales,
                        'entries_info' => "Showing {$sales->firstItem()} to {$sales->lastItem()} of {$sales->total()} entries"
                    ])
                @endif
            </div>

        </div>
    </div>

<div id="salesDetailModal" class="fixed top-0 left-0 hidden w-full h-full bg-gray-800 bg-opacity-50 flex justify-center items-center bg-gray-500 bg-opacity-50 hidden z-[999]">
    <div class="bg-white rounded-md shadow-lg w-2/5 max-w-lg">
        <div class="modal-header p-4 border-b border-gray-200 flex justify-between items-center">
            <h5 id="salesDetailModalLabel" class="text-xl font-medium text-gray-800">Detail Penjualan</h5>
            <button id="closeModal" class="text-gray-600 hover:text-gray-900">
                <i class="fas fa-times"></i>
            </button>
        </div>
            <div class="modal-body p-4">
                <!-- Header Modal -->
                <div class="mb-4">
                    <div class="grid grid-cols-2 gap-4 mb-2">
                        <div>
                            <p class="text-sm text-gray-500">Member Status</p>
                            <p id="memberStatus" class="font-medium">Non-Member</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">No Telephone</p>
                            <p id="memberPhone" class="font-medium">-</p>
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-4">
                        <div>
                            <p class="text-sm text-gray-500">Point Member</p>
                            <p id="memberPoints" class="font-medium">0</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Bergabung Sejak</p>
                            <p id="memberSince" class="font-medium">-</p>
                        </div>
                    </div>
                </div>

                <!-- Body Modal - Table -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nama Produk</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Qty</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Harga</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Sub Total</th>
                            </tr>
                        </thead>
                        <tbody id="salesDetailTableBody" class="bg-white divide-y divide-gray-200">
                            <!-- Data will be inserted here by JavaScript -->
                        </tbody>
                        <tfoot>
                            <tr class="bg-gray-50">
                                <td colspan="3" class="px-6 py-3 text-right text-sm font-medium text-gray-500">Total</td>
                                <td id="totalAmount" class="px-6 py-3 text-left text-sm font-medium text-gray-900">Rp 0</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Footer Modal -->
                <div class="mt-4 text-sm text-gray-500">
                    <p id="createdAt">Dibuat pada tanggal: -</p>
                    <p id="createdBy">Oleh: -</p>
                </div>
            </div>
            <div class="modal-footer p-4 border-t border-gray-200 flex justify-end">
                <button id="closeModalBtn" class="bg-gray-600 text-white px-4 py-2 rounded-md">Close</button>
            </div>
        </div>
    </div>
</div>

@endsection
