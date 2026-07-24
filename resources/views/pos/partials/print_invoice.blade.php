<div id="invoice_print_area" class="d-none">

    @php
        $grandTotal = 0;
    @endphp

    <div style="width:100%; max-width:600px; margin:0 auto; font-family:Arial, sans-serif; border:1px solid #ddd; border-radius:8px; padding:20px;">

        <!-- Logo -->
        <div style="text-align:center; margin-bottom:15px;">
            <img src="{{ getImage('settings', getInfo('logo')) }}" style="max-width:150px;">
        </div>

        <!-- Company Info -->
        <div style="text-align:center; margin-bottom:20px;">
            <h2>{{ getInfo('title') }}</h2>
            <p>Phone: {{ getInfo('phone') }}</p>
            <p>Email: {{ getInfo('email') }}</p>
        </div>

        <!-- Customer Info -->
        <div style="display:flex; justify-content:space-between; font-size:12px;">
            <div>
                <strong>Name:</strong> {{  'Walk-in Customer' }}<br>
                <strong>Invoice:</strong> 
            </div>
            <div>
                <strong>Mobile:</strong> <br>
                <strong>Date:</strong> {{ now()->format('d M Y') }}
            </div>
        </div>

        <hr>

        <!-- Items -->
        <table width="100%" border="1" cellspacing="0" cellpadding="5" style="font-size:12px;">
            <thead>
                <tr>
                    <th align="left">Item</th>
                    <th align="center">Qty</th>
                    <th align="right">Price</th>
                    <th align="right">Total</th>
                </tr>
            </thead>
            <tbody>
                @foreach($olditems as $item)
                    @php
                        $lineTotal = $item->sell_price * $item->ordered_qty;
                        $grandTotal += $lineTotal;
                    @endphp
                    <tr>
                        <td>{{ $item->product_name }}</td>
                        <td align="center">{{ $item->ordered_qty }}</td>
                        <td align="right">{{ number_format($item->sell_price,2) }}</td>
                        <td align="right">{{ number_format($lineTotal,2) }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>

        <hr>

        <!-- Total -->
        <div style="text-align:right; font-weight:bold;">
            Total Bill: {{ number_format($grandTotal,2) }}
        </div>

        <p style="text-align:center; margin-top:10px;">
            Thank you for shopping with us
        </p>

    </div>
</div>
