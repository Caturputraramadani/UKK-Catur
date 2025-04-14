<!DOCTYPE html>
<html>
<head>
    <meta charset="UTF-8">
    <title>{{ $title }}</title>
    <style>
        body { font-family: DejaVu Sans, sans-serif; }
        .header { text-align: center; margin-bottom: 20px; }
        .invoice-title { font-size: 24px; font-weight: bold; }
        .shop-info { margin-bottom: 30px; }
        .member-info { margin-bottom: 15px; }
        table { width: 100%; border-collapse: collapse; margin-bottom: 20px; background-color: #f4f4f4; }
        th, td { padding: 10px 15px; text-align: left; }
        th { background-color: #dcdcdc; }
        td { background-color: #fff; }
        .total-section { margin-top: 20px; text-align: right; }
        .thank-you { margin-top: 30px; text-align: center; font-style: italic; }
        .text-right { text-align: right; }
        .text-center { text-align: center; }
        .text-bold { font-weight: bold; }
        .border-top { border-top: 1px solid #000; }
        .total-row td {
            font-weight: bold;
        }
        .summary { background-color: #f0f0f0; padding: 10px; }
        .summary td { font-weight: bold; }
    </style>
</head>
<body>
    <div class="header">
        <div class="invoice-title">INVOICE</div>
        <div>Spike Store</div>
        <div>Jl. Raya Puncak No. 123, City</div>
        <div>Phone: 555-9725-555</div>
    </div>

    <div class="flex justify-between">
        <div class="member-info">
            @if($sale->member)
            Member Status: Member<br>
            Member Phone: {{ $sale->member->no_telephone }}<br>
            Member Since: {{ $sale->member->created_at->format('d F Y') }}<br>
            Member Points: {{ $sale->member->point }}
            @else
                Member Status: Non-Member
            @endif
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th>Product Name</th>
                <th class="text-right">Price</th>
                <th class="text-right">Quantity</th>
                <th class="text-right">Subtotal</th>
            </tr>
        </thead>
        <tbody>
            @foreach($sale->saleDetails as $detail)
            <tr>
                <td>{{ $detail->product->name }}</td>
                <td class="text-right">Rp {{ number_format($detail->product->price, 0, ',', '.') }}</td>
                <td class="text-right">{{ $detail->quantity_product }}</td>
                <td class="text-right">Rp {{ number_format($detail->total_price, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>

        <table class="summary">
            <tr>
                <td>Total Harga</td>
                <td class="text-right">Rp {{ number_format($sale->saleDetails->sum('total_price'), 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Poin Digunakan</td>
                <td class="text-right">{{ $sale->point_used }}</td>
            </tr>
            <tr>
                <td>Harga Setelah Poin</td>
                <td class="text-right">Rp {{ number_format($sale->sub_total, 0, ',', '.') }}</td>
            </tr>
            <tr>
                <td>Total Kembalian</td>
                <td class="text-right">Rp {{ number_format($sale->change, 0, ',', '.') }}</td>
            </tr>
        </table>


    <div class="thank-you">
        <div>
            <div class="text-bold">INVOICE #{{ $sale->id }}</div>
            <div>Date: {{ \Carbon\Carbon::parse($sale->date)->format('d F Y') }}</div>
            <div>Cashier: {{ $sale->user->name }}</div>
        </div>

        <p>Thank you for shopping at Spike Store</p>
        <p>Items purchased cannot be exchanged or returned</p>
    </div>
</body>
</html>
