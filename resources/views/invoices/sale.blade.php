<!-- resources/views/invoices/sale.blade.php -->
<!DOCTYPE html>
<html>
<head>
    <title>Invoice {{ $sale->invoice_no }}</title>
    <style> body { font-family: sans-serif; } table { width: 100%; border-collapse: collapse; } </style>
</head>
<body>
    <h2>Invoice: {{ $sale->invoice_no }}</h2>
    <p>Customer: {{ $sale->customer->name ?? 'Walk-in' }}</p>
    <p>Date: {{ $sale->sale_date }}</p>
    <!-- Items Table -->
    <table border="1">
        <tr><th>Item</th><th>Qty</th><th>Price</th></tr>
        @foreach($sale->items as $item)
            <tr>
                <td>{{ $item->product_name_snapshot }}</td>
                <td>{{ $item->qty }}</td>
                <td>{{ $item->line_total }}</td>
            </tr>
        @endforeach
    </table>
    <h3>Total: {{ $sale->final_amount }} BDT</h3>
</body>
</html>