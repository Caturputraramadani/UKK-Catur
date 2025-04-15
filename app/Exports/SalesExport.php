<?php
namespace App\Exports;

use Carbon\Carbon;

use App\Models\Sale;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithEvents;
use Maatwebsite\Excel\Concerns\ShouldAutoSize;
use Maatwebsite\Excel\Events\BeforeSheet;

class SalesExport implements FromCollection, WithHeadings, WithMapping, WithEvents, ShouldAutoSize
{
    protected $mappedData = [];

    public function collection()
    {
        $sales = Sale::with(['member', 'saleDetails.product', 'user'])->latest()->get();

        foreach ($sales as $sale) {
            $first = true;

            foreach ($sale->saleDetails as $detail) {
                $row = [
                    $first ? ($sale->member ? $sale->member->name : 'Non-Member') : '',
                    $first ? ($sale->member ? $sale->member->no_telephone : '-') : '',
                    $first ? ($sale->member ? $sale->member->point : 0) : '',
                    $detail->product->name,
                    $detail->quantity_product,
                    $first ? 'Rp ' . number_format($sale->sub_total + $sale->point_used, 0, ',', '.') : '',
                    $first ? 'Rp ' . number_format($sale->amount_paid, 0, ',', '.') : '',
                    $first ? 'Rp ' . number_format($sale->point_used, 0, ',', '.') : '',
                    $first ? 'Rp ' . number_format($sale->change, 0, ',', '.') : '',
                    $sale->date = Carbon::parse($sale->created_at),
                    $first ? $sale->user->name : '',
                ];

                $this->mappedData[] = $row;
                $first = false;
            }
        }

        return collect($this->mappedData);
    }

    public function map($row): array
    {
        // Sudah dipetakan manual di atas
        return $row;
    }

    public function headings(): array
    {
        return [
            'Nama Pelanggan',
            'No HP Pelanggan',
            'Poin Pelanggan',
            'Produk',
            'Quantity',
            'Total Harga',
            'Total Bayar',
            'Total Diskon Poin',
            'Total Kembalian',
            'Tanggal Pembelian',
            'Dibuat Oleh',
        ];
    }

    public function registerEvents(): array
    {
        return [
            BeforeSheet::class => function (BeforeSheet $event) {
                // Menyisipkan judul di atas header
                $event->sheet->insertNewRowBefore(1, 1);
                $event->sheet->mergeCells('A1:K1');
                $event->sheet->setCellValue('A1', 'Sales Report Spike Store');
            },
        ];
    }
}
