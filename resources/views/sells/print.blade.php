<div style="width:100%; max-width:600px; margin:0 auto; font-family:Arial,sans-serif; border:1px solid #ddd; border-radius:8px; padding:20px;">
    <!-- Logo -->
    <div style="text-align:center; margin-bottom:15px;">
        <img src="{{ getImage('settings',getInfo('logo'))}}" alt="Receipt Logo" style="max-width:150px; height:auto;">
    </div>

    <!-- Company Info -->
    <div style="text-align:center; margin-bottom:20px;">
        <h2 style="margin:0; font-size:18px;">{{ getInfo('title') }}</h2>
        <p style="margin:2px 0; font-size:12px; color:#555;">Phone: {{ getInfo('phone') }}</p>
        <p style="margin:2px 0; font-size:12px; color:#555;">Email: {{ getInfo('email') }}</p>
    </div>

    <!-- Customer Info -->
    <div style="display:flex; justify-content:space-between; margin-bottom:15px; font-size:12px;">
        <div>
            <p style="margin:2px 0; font-size:12px; color:#555;">Customer Info</p>
            <p style="margin:2px 0;"><strong>Name:</strong> {{ $transaction->contact->name ?? '' }}</p>
            <p style="margin:2px 0;"><strong>Invoice No:</strong> {{ $transaction->invoice_no }}</p>
            <p style="margin:2px 0;"><strong>Mobile:</strong> {{ $transaction->contact->mobile ?? '' }}</p>
            <p style="margin:2px 0;"><strong>Date:</strong> {{ $transaction->transaction_date }}</p>
        </div>
        <div>
            @if($transaction->shipping)
                <p style="margin:2px 0; font-size:12px; color:#555;">Shipping Info</p>
                <p style="margin:2px 0;"><strong>Name:</strong> {{ $transaction->shipping->name }}</p>
                <p style="margin:2px 0;"><strong>Phone:</strong> {{ $transaction->shipping->phone }}</p>
                <p style="margin:2px 0;"><strong>Address:</strong> {{ $transaction->shipping->address }}</p>
            @endif
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
            
            @php
                $sub_total=0;
            @endphp
                                        
            @foreach($transaction->lines()->get() as $line)

                @php
                    $sub_total += $line->price * $line->quantity;
                @endphp
            
                <tr>
                    <td style="padding:5px; border:1px solid #ddd;">
                        {{ ($transaction->invoice_type == 2 && $line->product?->name_bangla)
                            ? $line->product?->name_bangla
                            : ($line->product?->name ?? 'N/A') }}
            
                        {{ ($line->product?->type === 'variable')
                            ? ($line->variation?->name ?? '')
                            : '' }}
                    </td>
            
                    <td style="text-align:center; padding:5px; border:1px solid #ddd;">
                        {{ $line->quantity }}
                    </td>
            
                    <td style="text-align:right; padding:5px; border:1px solid #ddd;">
                        {{ $line->price }}
                    </td>
            
                    <td style="text-align:right; padding:5px; border:1px solid #ddd;">
                        {{ $line->price * $line->quantity }}
                    </td>
                </tr>
            
            @endforeach

        </tbody>
    </table>

    <!-- Summary -->
    @php $due = $transaction->final_amount - $transaction->payments->sum('amount'); @endphp
    <table style="width:100%; font-size:12px; border-collapse:collapse; margin-bottom:15px;">
        <tr>
            <!-- Left 60% Blank -->
            <td style="width:60%;"></td>
    
            <!-- Right 40% Summary -->
            <td style="width:40%;">
                <table style="width:100%; border-collapse:collapse;">
                    <tbody>
                        <tr>
                            <td style="padding:5px; font-weight:600;">Sub Total :</td>
                            <td style="padding:5px; text-align:right;">{{ priceFormate($sub_total) }}</td>
                        </tr>
                        @if($transaction->discount_amount)
                        <tr>
                            <td style="padding:5px; font-weight:600;">
                                Discount : {{ $transaction->discount_type=='Percentage' ? $transaction->discount_amount.' %' : '' }}
                            </td>
                            <td style="padding:5px; text-align:right;">{{ priceFormate($transaction->cal_discount) }}</td>
                        </tr>
                        @endif
                        @if($transaction->shipping_charge)
                        <tr>
                            <td style="padding:5px; font-weight:600;">Shipping :</td>
                            <td style="padding:5px; text-align:right;">{{ priceFormate($transaction->shipping_charge) }}</td>
                        </tr>
                        @endif
                        <tr>
                            <td style="padding:5px; font-weight:700; border-top:2px solid #ddd;">Total Bill :</td>
                            <td style="padding:5px; text-align:right; font-weight:700; border-top:2px solid #ddd;">{{ priceFormate($transaction->final_amount) }}</td>
                        </tr>
                        <tr>
                            <td style="padding:5px; font-weight:600;">Paid :</td>
                            <td style="padding:5px; text-align:right;">{{ priceFormate($transaction->payments->sum('amount')) }}</td>
                        </tr>
                        @if($due)
                        <tr>
                            <td style="padding:5px; font-weight:600;">Due :</td>
                            <td style="padding:5px; text-align:right; color:#d9534f; font-weight:600;">{{ priceFormate($due) }}</td>
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

<script>
    window.onload = function() {
        window.print();
    };
</script>
