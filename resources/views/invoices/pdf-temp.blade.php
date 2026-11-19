<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Invoice {{ $sale->invoice_no }}</title>
    <style>
        /* তোমার দেওয়া CSS গুলো এখানে পেস্ট করো */
        /* 🟢 Note: PDF এ জাভাস্ক্রিপ্ট কাজ করে না, তাই শুধু CSS রাখো */
        body { font-family: 'DejaVu Sans', sans-serif; background-color: #f1f5f9; padding: 16px; }
        .invoice-wrapper { max-width: 860px; margin: 0 auto; background: #ffffff; border-radius: 20px; box-shadow: 0 10px 30px -5px rgba(15, 23, 42, 0.07); }
        /* ... তোমার বাকি CSS ... */
        /* Please paste your CSS here */
    </style>
</head>
<body>
  <div class="invoice-wrapper">
    <header class="invoice-header" style="background: #0f172a; color: white; padding: 28px 24px;">
      <div class="header-top" style="display: flex; justify-content: space-between;">
        <div class="brand-block">
          <div class="brand-logo" style="background: #10b981; width: 46px; height: 46px; border-radius: 12px; display: inline-block; text-align: center; line-height: 46px; font-weight: bold;">OP</div>
          <div class="brand-info" style="display: inline-block; margin-left: 12px;">
            <h1 style="font-size: 20px; margin: 0;">{{ $company_name }}</h1>
            <p style="font-size: 12px; color: #cbd5e1; margin: 2px 0;">{{ $company_address }}</p>
            <p style="font-size: 12px; color: #cbd5e1; margin: 0;">{{ $company_phone }}</p>
          </div>
        </div>
        <div class="invoice-badge-box" style="text-align: right;">
          <div class="invoice-title" style="font-size: 11px; color: #94a3b8;">TAX & SALES INVOICE</div>
          <div class="invoice-number" style="font-size: 18px; font-weight: bold;">#{{ $sale->invoice_no }}</div>
          <div class="status-pill" style="background: rgba(16, 185, 129, 0.16); color: #6ee7b7; padding: 4px 11px; border-radius: 999px; display: inline-block; margin-top: 8px; font-size: 11px;">
            {{ strtoupper(str_replace('_', ' ', $sale->payment_status)) }}
          </div>
        </div>
      </div>
    </header>

    <section class="meta-grid" style="display: grid; grid-template-columns: repeat(4, 1fr); gap: 12px; padding: 18px 24px; background: #f8fafc; border-bottom: 1px solid #e2e8f0;">
      <div class="meta-item">
        <label style="font-size: 11px; color: #64748b; text-transform: uppercase;">Invoice Date</label>
        <span style="display: block; font-size: 13.5px; font-weight: bold;">{{ \Carbon\Carbon::parse($sale->sale_date)->format('d M, Y') }}</span>
      </div>
      <div class="meta-item">
        <label style="font-size: 11px; color: #64748b; text-transform: uppercase;">Payment Method</label>
        <span style="display: block; font-size: 13.5px; font-weight: bold;">{{ $sale->payments->first()->method ?? 'N/A' }}</span>
      </div>
    </section>

    <section class="address-section" style="display: grid; grid-template-columns: 1fr 1fr; gap: 16px; padding: 22px 24px; border-bottom: 1px solid #e2e8f0;">
      <div class="address-card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px 16px;">
        <h3 style="font-size: 11px; color: #64748b; text-transform: uppercase;">Billed To</h3>
        <div class="name" style="font-size: 15px; font-weight: bold;">{{ $sale->customer->name ?? 'Guest Customer' }}</div>
        <p style="font-size: 13px; color: #64748b; margin-top: 3px;">{{ $sale->customer->phone ?? '' }}</p>
        <p style="font-size: 13px; color: #64748b; margin-top: 3px;">{{ $sale->customer->email ?? '' }}</p>
      </div>
      
      <div class="address-card" style="background: #ffffff; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px 16px;">
        <h3 style="font-size: 11px; color: #64748b; text-transform: uppercase;">Shipped To</h3>
        <!-- 🟢 NOTE: এখানে shipping_address_id যোগ করে ডাটাবেস থেকে আসল ঠিকানা আনো -->
        <div class="name" style="font-size: 15px; font-weight: bold;">Default Shipping Address</div>
        <p style="font-size: 13px; color: #64748b; margin-top: 3px;">Please update address from DB</p>
      </div>
    </section>

    <section class="items-section" style="padding: 22px 24px;">
      <div class="table-header" style="display: grid; grid-template-columns: 2.6fr 0.9fr 0.6fr 0.9fr 0.8fr 1fr; gap: 10px; padding: 11px 16px; background: #f8fafc; font-size: 11px; font-weight: bold; color: #64748b; text-transform: uppercase;">
        <div>Item Description</div>
        <div style="text-align: right;">Unit Price</div>
        <div style="text-align: right;">Qty</div>
        <div style="text-align: right;">Discount</div>
        <div style="text-align: right;">VAT</div>
        <div style="text-align: right;">Line Total</div>
      </div>

      @foreach($sale->items as $item)
      <div class="item-row" style="display: grid; grid-template-columns: 2.6fr 0.9fr 0.6fr 0.9fr 0.8fr 1fr; gap: 10px; align-items: center; padding: 14px 16px; border-bottom: 1px solid #e2e8f0;">
        <div>
          <div style="font-size: 14px; font-weight: bold;">{{ $item->product_name_snapshot }}</div>
          <div style="font-size: 11.5px; color: #64748b;">SKU: {{ $item->product->sku ?? 'N/A' }}</div>
        </div>
        <div style="text-align: right; font-size: 13.5px;">৳{{ number_format($item->unit_price_snapshot, 2) }}</div>
        <div style="text-align: right; font-size: 13.5px; font-weight: bold;">{{ $item->qty }}</div>
        <div style="text-align: right; font-size: 13.5px; color: #059669;">-৳{{ number_format($item->line_discount, 2) }}</div>
        <div style="text-align: right; font-size: 13.5px;">৳{{ number_format($item->tax_amount, 2) }}</div>
        <div style="text-align: right; font-size: 14px; font-weight: bold;">৳{{ number_format($item->line_total, 2) }}</div>
      </div>
      @endforeach
    </section>

    <section class="bottom-section" style="padding: 12px 24px 28px 24px; display: grid; grid-template-columns: 1.1fr 1fr; gap: 20px;">
      <div class="notes-box">
        <div class="payment-card" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 14px;">
          <h4 style="font-size: 11.5px; color: #64748b; text-transform: uppercase;">Payment Details</h4>
          <div style="display: flex; justify-content: space-between; font-size: 12.5px; margin-bottom: 5px;">
            <span>Paid Amount</span>
            <span style="font-weight: bold; color: #059669;">৳{{ number_format($sale->paid_amount, 2) }}</span>
          </div>
          <div style="display: flex; justify-content: space-between; font-size: 12.5px;">
            <span>Due Balance</span>
            <span style="font-weight: bold;">৳{{ number_format($sale->due_amount, 2) }}</span>
          </div>
        </div>
      </div>

      <div class="totals-card" style="background: #f8fafc; border: 1px solid #e2e8f0; border-radius: 12px; padding: 18px;">
        <div style="display: flex; justify-content: space-between; font-size: 13.5px; padding: 6px 0;">
          <span>Subtotal</span>
          <strong>৳{{ number_format($sale->sub_total, 2) }}</strong>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 13.5px; padding: 6px 0;">
          <span>Item Discount</span>
          <strong style="color: #059669;">-৳{{ number_format($sale->item_total_discount, 2) }}</strong>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 13.5px; padding: 6px 0;">
          <span>Invoice Discount</span>
          <strong style="color: #059669;">-৳{{ number_format($sale->discount_amount, 2) }}</strong>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 13.5px; padding: 6px 0;">
          <span>Tax</span>
          <strong>+৳{{ number_format($sale->tax_amount, 2) }}</strong>
        </div>
        <div style="display: flex; justify-content: space-between; font-size: 13.5px; padding: 6px 0;">
          <span>Shipping</span>
          <strong>+৳{{ number_format($sale->shipping_charge, 2) }}</strong>
        </div>
        <div style="height: 1px; background: #e2e8f0; margin: 10px 0;"></div>
        <div style="display: flex; justify-content: space-between; align-items: baseline;">
          <span style="font-size: 15px; font-weight: bold;">Grand Total</span>
          <span style="font-size: 24px; font-weight: bold; color: #0f172a;">৳{{ number_format($sale->final_amount, 2) }}</span>
        </div>
      </div>
    </section>
  </div>
</body>
</html>