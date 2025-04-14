@extends('layouts.main')
@section('container')
@php
    use Carbon\Carbon;
    $today = Carbon::today();
    $startDate = $today->copy()->startOfMonth();
    $startDate = Carbon::today()->subDays(21);
    $todaySalesCount =App\Models\Sale::whereDate('date', $today)->count();
@endphp



<div class="flex items-center text-gray-500 text-sm ml-2 mb-4">
    <i class="ti ti-home text-xl"></i>
    <i class="ti ti-chevron-right text-xl mx-2"></i>
    <span class="text-gray-500 font-semibold text-lg">Dashboard</span>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 lg:gap-x-6 gap-x-0 lg:gap-y-0 gap-y-6">
    <div class="col-span-3">
        <div class="card">
            <div class="card-body">
                <!-- Welcome Message with Role -->
                <div class="mb-6">
                    <h1 class="text-2xl font-semibold">Selamat Datang, {{ Auth::user()->role == 'admin' ? 'Admin' : 'Employee' }}.</h1>
                </div>

                @if (Auth::check() && Auth::user()->role === 'employee')
                <!-- Today's Sales Count Card -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-lg font-semibold mb-4">Total Penjualan Hari Ini</h2>

                    <div class="flex items-center space-x-4">
                        <!-- Circle with Number -->
                        <div class="w-20 h-20 rounded-full bg-blue-500 text-white flex items-center justify-center text-2xl font-bold shadow-md">
                            {{ $todaySalesCount }}
                        </div>

                        <!-- Description -->
                        <div>
                            <p class="text-gray-700 text-base">Jumlah transaksi penjualan hari ini</p>
                            <p class="text-sm text-gray-400 mt-1">Terakhir diperbarui: {{ $today->format('d M Y') }}</p>
                        </div>
                    </div>
                </div>
                @endif

                @if (Auth::check() && Auth::user()->role === 'admin')
                <!-- Sales Chart -->
                <div class="bg-white rounded-lg shadow p-6">
                    <h2 class="text-lg font-semibold mb-4">Grafik Penjualan Bulan {{ $today->translatedFormat('F Y') }}</h2>
                    <div class="chart-container" style="position: relative; height:300px; width:100%">
                        <canvas id="salesChart"></canvas>
                    </div>
                </div>

                <!-- Product Sales Pie Chart -->
                <div class="bg-white rounded-lg shadow p-6 mt-6">
                    <h2 class="text-lg font-semibold mb-4">Persentase Penjualan Produk</h2>
                    <div id="productChartContainer">
                        <div class="chart-container" style="position: relative; height:300px; width:100%">
                            <canvas id="productSalesChart"></canvas>
                        </div>
                        <div id="chartLoading" class="text-center py-4">
                            <div class="inline-block animate-spin rounded-full h-8 w-8 border-t-2 border-b-2 border-blue-500"></div>
                            <p class="mt-2 text-gray-600">Memuat data penjualan...</p>
                        </div>
                        <div id="chartError" class="hidden text-center py-4 text-red-500"></div>
                        <div id="chartEmpty" class="hidden text-center py-4 text-gray-500">
                            <i class="ti ti-package-off text-4xl mb-2"></i>
                            <p>Belum ada data penjualan produk</p>
                        </div>
                    </div>
                </div>
                @endif
            </div>

        </div>


    </div>
</div>


@endsection
