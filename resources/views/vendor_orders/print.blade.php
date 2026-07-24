<div style="width:100%; max-width:600px; margin:0 auto; font-family:Arial, sans-serif; border:1px solid #ddd; border-radius:8px; padding:20px;">
    <!-- Logo -->
    <div style="text-align:center; margin-bottom:15px;">
        <a href="javascript:void(0);">
            <img src="{{ getImage('settings',getInfo('logo'))}}" alt="Receipt Logo" style="max-width:150px; height:auto;">
        </a>
    </div>

    <!-- Company Info -->
    <div style="text-align:center; margin-bottom:20px;">
        <h2 style="margin:0; font-size:18px;">{{ getInfo('title') }}</h2>
        <p style="margin:2px 0; font-size:12px; color:#555;">Phone: {{ getInfo('phone') }}</p>
        <p style="margin:2px 0; font-size:12px; color:#555;">Email: <a href="mailto:{{ getInfo('email') }}" style="color:#007bff; text-decoration:none;">{{ getInfo('email') }}</a></p>
    </div>

    <!-- Customer Info -->
    <div style="display:flex; justify-content:space-between; flex-wrap:wrap; margin-bottom:15px; font-size:12px;">
        <div style="margin-bottom:5px;">
            <p style="margin:2px 0;"><strong>Name:</strong> {{ $order->transaction->contact->name ?? '' }}</p>
            <p style="margin:2px 0;"><strong>Invoice No:</strong> {{ $order->invoice_no }}</p>
        </div>
        <div style="margin-bottom:5px;">
            <p style="margin:2px 0;"><strong>Customer Mobile:</strong> {{ $order->transaction->contact->mobile ?? '' }}</p>
            <p style="margin:2px 0;"><strong>Date:</strong> {{ $order->transaction_date }}</p>
        </div>
    </div>

    <!-- Items Table -->
    <table style="width:100%; border-collapse:collapse; font-size:12px; margin-bottom:15px;">
        <thead>
            <tr style="background:#f2f2f2;">
                <th style="text-align:left; padding:5px; border:1px solid #ddd;"># Item</th>
                <th style="text-align:center; padding:5px; border:1px solid #ddd;">Qty</th>
                <th style="text-align:right; padding:5px; border:1px solid #ddd;">Price</th>
                <th style="text-align:right; padding:5px; border:1px solid #ddd;">Total</th>
            </tr>
        </thead>
        <tbody>
            @foreach($order->lines as $line)
            <tr>
                <td style="padding:5px; border:1px solid #ddd;">{{ $line->product->name }} {{ $line->product->type=='variable' ? $line->variation->name:'' }}</td>
                <td style="text-align:center; padding:5px; border:1px solid #ddd;">{{ $line->quantity }}</td>
                <td style="text-align:right; padding:5px; border:1px solid #ddd;">{{ $line->price }}</td>
                <td style="text-align:right; padding:5px; border:1px solid #ddd;">{{ $line->price * $line->quantity }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>


    <!-- Summary -->
@php $due = $order->final_amount - $order->payments->sum('amount'); @endphp
<table style="width:100%; font-size:12px; border-collapse:collapse; margin-bottom:15px;">
    <tr>
        <!-- Left Blank Space -->
        <td style="width:60%;"></td>

        <!-- Right Summary Data -->
        <td style="width:40%;">
            <table style="width:100%; border-collapse:collapse;">
                <tbody>
                    <tr>
                        <td style="padding:8px 5px; font-weight:600;">Sub Total</td>
                        <td style="padding:8px 5px; text-align:right;">{{ priceFormate($order->final_amount) }}</td>
                    </tr>
                    @if($order->discount_amount)
                    <tr>
                        <td style="padding:8px 5px; font-weight:600;">Discount</td>
                        <td style="padding:8px 5px; text-align:right;">- {{ priceFormate($order->discount_amount) }}</td>
                    </tr>
                    @endif
                    @if($order->shipping_charge)
                    <tr>
                        <td style="padding:8px 5px; font-weight:600;">Shipping</td>
                        <td style="padding:8px 5px; text-align:right;">{{ priceFormate($order->shipping_charge) }}</td>
                    </tr>
                    @endif
                    <tr>
                        <td style="padding:8px 5px; font-weight:700; border-top:2px solid #ddd;">Total Bill</td>
                        <td style="padding:8px 5px; text-align:right; font-weight:700; border-top:2px solid #ddd;">{{ priceFormate($order->final_amount) }}</td>
                    </tr>
                    <tr>
                        <td style="padding:8px 5px; font-weight:600;">Paid</td>
                        <td style="padding:8px 5px; text-align:right;">{{ priceFormate($order->payments->sum('amount')) }}</td>
                    </tr>
                    @if($due)
                    <tr>
                        <td style="padding:8px 5px; font-weight:600;">Due</td>
                        <td style="padding:8px 5px; text-align:right; color:#d9534f; font-weight:600;">{{ priceFormate($due) }}</td>
                    </tr>
                    @endif
                </tbody>
            </table>
        </td>
    </tr>
</table>


    <!-- Footer -->
    <div style="text-align:center; font-size:12px; color:#555; border-top:1px dashed #ccc; padding-top:10px;">
        <p>Thank You For Shopping With Us. Please Come Again</p>
    </div>
</div>
