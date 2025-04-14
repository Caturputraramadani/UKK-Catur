@extends('layouts.main')
@section('container')


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
                    <h1 class="text-2xl font-semibold">Selamat Datang,.</h1>
                </div>


                <!-- Today's Sales Count Card -->
                <div class="bg-white rounded-lg shadow p-6 mb-6">
                    <h2 class="text-lg font-semibold mb-4">Total Penjualan Hari Ini</h2>

                    <div class="flex items-center space-x-4">
                        <!-- Circle with Number -->
                        <div class="w-20 h-20 rounded-full bg-blue-500 text-white flex items-center justify-center text-2xl font-bold shadow-md">

                        </div>

                        <!-- Description -->
                        <div>
                            <p class="text-gray-700 text-base">Jumlah transaksi penjualan hari ini</p>

                        </div>
                    </div>
                </div>
              


            </div>

        </div>


    </div>
</div>


@endsection
