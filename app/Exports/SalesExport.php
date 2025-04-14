<?php

namespace App\Exports;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class SalesExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Sale::with(['member', 'saleDetails.product', 'user'])->latest()->get();
    }

    public function headings(): array
    {
        return [
            'Nama Pelanggan',
            'No HP Pelanggan',
            'Poin Pelanggan',
            'Produk',
            'Total Harga',
            'Total Bayar',
            'Total Diskon Poin',
            'Total Kembalian',
            'Tanggal Pembelian',
            'Dibuat Oleh'
        ];
    }

    public function map($sale): array
    {
        // Gabungkan semua produk dalam satu string
        $products = $sale->saleDetails->map(function($detail) {
            return $detail->product->name . ' (' . $detail->quantity_product . 'x)';
        })->implode(', ');

        // Konversi date ke Carbon jika belum
        $date = is_string($sale->date)
            ? \Carbon\Carbon::parse($sale->date)
            : $sale->date;

        return [
            $sale->member ? $sale->member->name : 'Non-Member',
            $sale->member ? $sale->member->no_telephone : '-',
            $sale->member ? $sale->member->point : 0,
            $products,
            'Rp ' . number_format($sale->sub_total + $sale->point_used, 0, ',', '.'),
            'Rp ' . number_format($sale->amount_paid, 0, ',', '.'),
            'Rp ' . number_format($sale->point_used, 0, ',', '.'),
            'Rp ' . number_format($sale->change, 0, ',', '.'),
            $date->format('d-m-Y'),  // Format tanggal yang sudah diparse
            $sale->user->name
        ];
    }
}
