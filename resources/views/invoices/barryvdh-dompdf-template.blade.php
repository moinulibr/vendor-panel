<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <title>Invoice #{{ $sale->invoice_no ?? 'INV-2026-8492' }}</title>
  <style>
    @page {
      margin: 18px 20px;
    }

    * {
      box-sizing: border-box;
    }

    body {
      font-family: DejaVu Sans, sans-serif;
      font-size: 11px;
      color: #0f172a;
      line-height: 1.45;
      margin: 0;
      padding: 0;
      background: #ffffff;
    }

    table {
      width: 100%;
      border-collapse: collapse;
    }

    .text-right { text-align: right; }
    .text-center { text-align: center; }
    .text-left { text-align: left; }
    .bold { font-weight: bold; }
    .muted { color: #64748b; }
    .emerald { color: #059669; }

    /* Header Banner */
    .header-box {
      background-color: #0f172a;
      color: #ffffff;
      padding: 18px 20px;
      border-radius: 8px;
    }

    .brand-badge {
      display: inline-block;
      background-color: #059669;
      color: #ffffff;
      font-weight: bold;
      font-size: 15px;
      padding: 8px 11px;
      border-radius: 6px;
      margin-right: 10px;
    }

    .company-name {
      font-size: 17px;
      font-weight: bold;
      color: #ffffff;
      margin: 0 0 3px 0;
    }

    .company-sub {
      font-size: 10px;
      color: #cbd5e1;
      margin: 1px 0;
    }

    .invoice-label {
      font-size: 9px;
      text-transform: uppercase;
      letter-spacing: 1px;
      color: #94a3b8;
    }

    .invoice-no {
      font-size: 16px;
      font-weight: bold;
      color: #ffffff;
      margin: 2px 0 6px 0;
    }

    .status-badge {
      display: inline-block;
      background-color: #065f46;
      color: #d1fae5;
      font-size: 9px;
      font-weight: bold;
      padding: 3px 9px;
      border-radius: 12px;
      text-transform: uppercase;
    }

    /* Meta Strip */
    .meta-table {
      margin-top: 12px;
      background-color: #f8fafc;
      border: 1px solid #e2e8f0;
    }

    .meta-table td {
      padding: 9px 12px;
      border-right: 1px solid #e2e8f0;
      vertical-align: top;
    }

    .meta-table td:last-child {
      border-right: none;
    }

    .meta-title {
      font-size: 9px;
      text-transform: uppercase;
      color: #64748b;
      margin-bottom: 2px;
    }

    .meta-val {
      font-size: 11px;
      font-weight: bold;
      color: #0f172a;
    }

    /* Address Cards */
    .address-table {
      margin-top: 12px;
    }

    .address-cell {
      width: 50%;
      vertical-align: top;
    }

    .address-card {
      border: 1px solid #e2e8f0;
      border-radius: 6px;
      padding: 10px 12px;
      background-color: #ffffff;
    }

    .address-heading {
      font-size: 9px;
      text-transform: uppercase;
      color: #64748b;
      font-weight: bold;
      margin-bottom: 4px;
      border-bottom: 1px solid #f1f5f9;
      padding-bottom: 3px;
    }

    .customer-name {
      font-size: 12px;
      font-weight: bold;
      color: #0f172a;
      margin-bottom: 2px;
    }

    .address-line {
      font-size: 10px;
      color: #475569;
      margin: 1px 0;
    }

    /* Items Table */
    .section-title {
      font-size: 10px;
      font-weight: bold;
      text-transform: uppercase;
      color: #475569;
      margin: 14px 0 6px 0;
      letter-spacing: 0.5px;
    }

    .items-table {
      border: 1px solid #e2e8f0;
    }

    .items-table thead th {
      background-color: #0f172a;
      color: #ffffff;
      font-size: 9.5px;
      text-transform: uppercase;
      padding: 8px 8px;
      border-right: 1px solid #1e293b;
    }

    .items-table tbody td {
      padding: 8px 8px;
      border-bottom: 1px solid #e2e8f0;
      border-right: 1px solid #f1f5f9;
      vertical-align: top;
      font-size: 10.5px;
    }

    .items-table tbody tr:nth-child(even) td {
      background-color: #fcfcfd;
    }

    .item-title {
      font-weight: bold;
      color: #0f172a;
      font-size: 11px;
    }

    .item-meta {
      font-size: 9px;
      color: #64748b;
      margin-top: 2px;
    }

    .item-promo {
      display: inline-block;
      margin-top: 3px;
      background-color: #ecfdf5;
      color: #059669;
      font-size: 8.5px;
      font-weight: bold;
      padding: 1px 5px;
      border-radius: 3px;
    }

    /* Bottom Summary */
    .bottom-table {
      margin-top: 14px;
    }

    .left-col {
      width: 52%;
      vertical-align: top;
      padding-right: 10px;
    }

    .right-col {
      width: 48%;
      vertical-align: top;
      padding-left: 6px;
    }

    .coupon-box {
      background-color: #ecfdf5;
      border: 1px dashed #059669;
      border-radius: 6px;
      padding: 9px 11px;
      margin-bottom: 10px;
    }

    .coupon-title {
      font-size: 10.5px;
      font-weight: bold;
      color: #065f46;
    }

    .coupon-sub {
      font-size: 9.5px;
      color: #047857;
    }

    .info-box {
      border: 1px solid #e2e8f0;
      border-radius: 6px;
      padding: 10px 12px;
      background-color: #f8fafc;
    }

    .info-box-title {
      font-size: 9.5px;
      text-transform: uppercase;
      font-weight: bold;
      color: #475569;
      margin-bottom: 6px;
    }

    .totals-box {
      border: 1px solid #e2e8f0;
      border-radius: 6px;
      padding: 10px 12px;
      background-color: #f8fafc;
    }

    .totals-table td {
      padding: 4px 2px;
      font-size: 10.5px;
    }

    .total-savings-row td {
      background-color: #ecfdf5;
      color: #065f46;
      font-weight: bold;
      padding: 6px 8px;
      border-radius: 4px;
    }

    .grand-total-row td {
      border-top: 2px solid #0f172a;
      padding-top: 8px;
      margin-top: 6px;
      font-size: 13.5px;
      font-weight: bold;
      color: #0f172a;
    }

    .footer-note {
      margin-top: 18px;
      padding-top: 10px;
      border-top: 1px solid #e2e8f0;
      font-size: 9.5px;
      color: #64748b;
    }
  </style>
</head>
<body>

  @php
    // Dummy fallback data (replace with real $sale properties when wired)
    $invoiceNo     = $sale->invoice_no ?? 'INV-2026-8492';
    $orderNo       = $sale->order_no ?? 'ORD-99412';
    $invoiceDate   = $sale->created_at?->format('d M, Y') ?? '19 Nov, 2026';
    $paymentMethod = $sale->payment_method ?? 'bKash / SSLCommerz';
    $courierName   = $sale->courier_name ?? 'Pathao Express';
    $trackingNo    = $sale->tracking_no ?? 'PTH-8829104';

    $items = $sale->items ?? collect([
      (object)[
        'name' => 'Sundarbans Raw Wild Honey (1kg Jar)',
        'sku' => 'PO-HNY-1000',
        'batch' => 'Batch #B26-09',
        'promo' => '10% Item Discount Applied',
        'unit_price' => 1200.00,
        'qty' => 2,
        'discount' => 240.00,
        'tax' => 108.00,
        'total' => 2268.00,
      ],
      (object)[
        'name' => 'Premium Pabna Danadar Cow Ghee (500g)',
        'sku' => 'PO-GHE-500',
        'batch' => 'Glass Jar',
        'promo' => 'Flat BDT 100 Off',
        'unit_price' => 900.00,
        'qty' => 1,
        'discount' => 100.00,
        'tax' => 40.00,
        'total' => 840.00,
      ],
      (object)[
        'name' => 'Cold-Pressed Extra Virgin Coconut Oil (500ml)',
        'sku' => 'PO-OIL-500',
        'batch' => 'Organic Certified',
        'promo' => null,
        'unit_price' => 650.00,
        'qty' => 1,
        'discount' => 0.00,
        'tax' => 32.50,
        'total' => 682.50,
      ],
    ]);

    $grossSubtotal  = 3950.00;
    $itemDiscount   = 340.00;
    $couponCode     = 'WINTER200';
    $couponDiscount = 200.00;
    $totalDiscount  = $itemDiscount + $couponDiscount; // 540.00
    $taxableAmount  = $grossSubtotal - $totalDiscount; // 3410.00
    $totalTax       = 180.50;
    $shippingCharge = 60.00;
    $grandTotal     = $taxableAmount + $totalTax + $shippingCharge; // 3650.50
  @endphp

  <!-- Header -->
  <div class="header-box">
    <table>
      <tr>
        <td style="width: 62%; vertical-align: middle;">
          <table>
            <tr>
              <td style="width: 48px; vertical-align: middle;">
                <span class="brand-badge">PO</span>
              </td>
              <td style="vertical-align: middle;">
                <div class="company-name">{{ $company_name ?? 'PureOlaa Naturals' }}</div>
                <div class="company-sub">{{ $company_address ?? 'Mirpur-1, Dhaka-1216 • BIN: 004829104-0203' }}</div>
                <div class="company-sub">{{ $company_phone ?? '+880 1712-794033' }} • support@pureolaa.com</div>
              </td>
            </tr>
          </table>
        </td>
        <td style="width: 38%; vertical-align: middle;" class="text-right">
          <div class="invoice-label">Tax &amp; Sales Invoice</div>
          <div class="invoice-no">#{{ $invoiceNo }}</div>
          <span class="status-badge">Paid in Full</span>
        </td>
      </tr>
    </table>
  </div>

  <!-- Meta Info -->
  <table class="meta-table">
    <tr>
      <td style="width: 25%;">
        <div class="meta-title">Invoice Date</div>
        <div class="meta-val">{{ $invoiceDate }}</div>
      </td>
      <td style="width: 25%;">
        <div class="meta-title">Order ID</div>
        <div class="meta-val">#{{ $orderNo }}</div>
      </td>
      <td style="width: 25%;">
        <div class="meta-title">Payment Method</div>
        <div class="meta-val">{{ $paymentMethod }}</div>
      </td>
      <td style="width: 25%;">
        <div class="meta-title">Delivery Courier</div>
        <div class="meta-val">{{ $courierName }}</div>
      </td>
    </tr>
  </table>

  <!-- Billing & Shipping -->
  <table class="address-table">
    <tr>
      <td class="address-cell" style="padding-right: 6px;">
        <div class="address-card">
          <div class="address-heading">Billed To</div>
          <div class="customer-name">Tanvir Ahmed Chowdhury</div>
          <div class="address-line">House 42, Road 07, Block C, Banani</div>
          <div class="address-line">Dhaka-1213, Bangladesh</div>
          <div class="address-line">+880 1819-442210 • tanvir.c@example.com</div>
        </div>
      </td>
      <td class="address-cell" style="padding-left: 6px;">
        <div class="address-card">
          <div class="address-heading">Shipped To</div>
          <div class="customer-name">Tanvir Ahmed (Office Desk)</div>
          <div class="address-line">Level 6, Quantum Tower, Gulshan Avenue</div>
          <div class="address-line">Dhaka-1212 • Tracking: <strong>{{ $trackingNo }}</strong></div>
          <div class="address-line">Delivery Zone: Inside Dhaka (Same Day)</div>
        </div>
      </td>
    </tr>
  </table>

  <!-- Line Items -->
  <div class="section-title">Purchased Items ({{ count($items) }})</div>
  <table class="items-table">
    <thead>
      <tr>
        <th class="text-left" style="width: 38%;">Item Description</th>
        <th class="text-right" style="width: 13%;">Unit Price</th>
        <th class="text-center" style="width: 7%;">Qty</th>
        <th class="text-right" style="width: 13%;">Item Disc.</th>
        <th class="text-right" style="width: 12%;">VAT (5%)</th>
        <th class="text-right" style="width: 17%;">Line Total</th>
      </tr>
    </thead>
    <tbody>
      @foreach($items as $item)
        <tr>
          <td>
            <div class="item-title">{{ $item->name }}</div>
            <div class="item-meta">SKU: {{ $item->sku }} • {{ $item->batch }}</div>
            @if(!empty($item->promo))
              <span class="item-promo">{{ $item->promo }}</span>
            @endif
          </td>
          <td class="text-right">BDT {{ number_format($item->unit_price, 2) }}</td>
          <td class="text-center bold">{{ $item->qty }}</td>
          <td class="text-right emerald">
            {{ $item->discount > 0 ? '-BDT ' . number_format($item->discount, 2) : 'BDT 0.00' }}
          </td>
          <td class="text-right">BDT {{ number_format($item->tax, 2) }}</td>
          <td class="text-right bold">BDT {{ number_format($item->total, 2) }}</td>
        </tr>
      @endforeach
    </tbody>
  </table>

  <!-- Bottom Section -->
  <table class="bottom-table">
    <tr>
      <td class="left-col">
        <div class="coupon-box">
          <table>
            <tr>
              <td>
                <div class="coupon-title">COUPON APPLIED: {{ $couponCode }}</div>
                <div class="coupon-sub">Promo code discount applied on order</div>
              </td>
              <td class="text-right bold emerald" style="font-size: 12px;">
                -BDT {{ number_format($couponDiscount, 2) }}
              </td>
            </tr>
          </table>
        </div>

        <div class="info-box">
          <div class="info-box-title">Payment &amp; Transaction Info</div>
          <table>
            <tr>
              <td class="muted" style="padding: 2px 0;">Transaction ID</td>
              <td class="text-right bold" style="padding: 2px 0;">TXN-98437219BK</td>
            </tr>
            <tr>
              <td class="muted" style="padding: 2px 0;">Payment Date</td>
              <td class="text-right bold" style="padding: 2px 0;">19 Nov 2026, 04:42 PM</td>
            </tr>
            <tr>
              <td class="muted" style="padding: 2px 0;">Paid Amount</td>
              <td class="text-right bold emerald" style="padding: 2px 0;">BDT {{ number_format($grandTotal, 2) }}</td>
            </tr>
            <tr>
              <td class="muted" style="padding: 2px 0;">Due Balance</td>
              <td class="text-right bold" style="padding: 2px 0;">BDT 0.00</td>
            </tr>
          </table>
        </div>
      </td>

      <td class="right-col">
        <div class="totals-box">
          <table class="totals-table">
            <tr>
              <td class="muted">Gross Subtotal</td>
              <td class="text-right bold">BDT {{ number_format($grossSubtotal, 2) }}</td>
            </tr>
            <tr>
              <td class="muted">Product-Level Discount</td>
              <td class="text-right bold emerald">-BDT {{ number_format($itemDiscount, 2) }}</td>
            </tr>
            <tr>
              <td class="muted">Coupon Discount ({{ $couponCode }})</td>
              <td class="text-right bold emerald">-BDT {{ number_format($couponDiscount, 2) }}</td>
            </tr>
            <tr class="total-savings-row">
              <td>Total Discount Saved</td>
              <td class="text-right">-BDT {{ number_format($totalDiscount, 2) }}</td>
            </tr>
            <tr>
              <td class="muted">Net Taxable Amount</td>
              <td class="text-right bold">BDT {{ number_format($taxableAmount, 2) }}</td>
            </tr>
            <tr>
              <td class="muted">Total VAT / Tax</td>
              <td class="text-right bold">+BDT {{ number_format($totalTax, 2) }}</td>
            </tr>
            <tr>
              <td class="muted">Shipping Charge</td>
              <td class="text-right bold">+BDT {{ number_format($shippingCharge, 2) }}</td>
            </tr>
            <tr class="grand-total-row">
              <td>Grand Total</td>
              <td class="text-right">BDT {{ number_format($grandTotal, 2) }}</td>
            </tr>
          </table>
        </div>
      </td>
    </tr>
  </table>

  <!-- Footer -->
  <div class="footer-note">
    <table>
      <tr>
        <td>Thank you for shopping with <strong>{{ $company_name ?? 'PureOlaa Naturals' }}</strong>!</td>
        <td class="text-right">Computer-generated invoice • No signature required</td>
      </tr>
    </table>
  </div>

</body>
</html>