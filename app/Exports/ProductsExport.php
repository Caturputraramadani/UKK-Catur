<?php

namespace App\Exports;

use App\Models\Product;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;

class ProductsExport implements FromCollection, WithHeadings, WithMapping
{
    public function collection()
    {
        return Product::all();
    }

    public function headings(): array
    {
        return [
            'Product Name',
            'Price',
            'Stock',
        ];
    }

    public function map($product): array
    {
        return [
            $product->name,
            'Rp ' . number_format($product->price, 0, ',', '.'),
            $product->stock,
        ];
    }
}
