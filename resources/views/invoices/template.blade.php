<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Sales Invoice #INV-2026-8492</title>
  <link rel="preconnect" href="https://fonts.googleapis.com">
  <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
  <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@400;500;600;700;800&display=swap" rel="stylesheet">
  <style>
    :root {
      --bg-page: #f1f5f9;
      --bg-card: #ffffff;
      --bg-subtle: #f8fafc;
      --primary: #0f172a;
      --accent: #059669;       /* Premium Emerald */
      --accent-soft: #ecfdf5;
      --accent-border: #a7f3d0;
      --brand: #2563eb;        /* Royal Blue Accent */
      --text-main: #0f172a;
      --text-muted: #64748b;
      --text-light: #94a3b8;
      --border: #e2e8f0;
      --danger: #dc2626;
      --radius-lg: 20px;
      --radius-md: 12px;
      --shadow-card: 0 10px 30px -5px rgba(15, 23, 42, 0.07), 0 4px 10px -2px rgba(15, 23, 42, 0.04);
    }

    * {
      box-sizing: border-box;
      margin: 0;
      padding: 0;
    }

    body {
      font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, sans-serif;
      background-color: var(--bg-page);
      color: var(--text-main);
      line-height: 1.5;
      padding: 16px;
      -webkit-font-smoothing: antialiased;
    }

    /* Top Action Bar (Hidden on Print) */
    .action-bar {
      max-width: 860px;
      margin: 0 auto 14px auto;
      display: flex;
      justify-content: space-between;
      align-items: center;
      gap: 10px;
    }

    .action-bar span {
      font-size: 13px;
      font-weight: 600;
      color: var(--text-muted);
    }

    .btn-group {
      display: flex;
      gap: 8px;
    }

    .btn {
      border: none;
      border-radius: 10px;
      padding: 9px 15px;
      font-family: inherit;
      font-size: 13px;
      font-weight: 600;
      cursor: pointer;
      display: inline-flex;
      align-items: center;
      gap: 6px;
      transition: all 0.2s ease;
    }

    .btn-outline {
      background: #ffffff;
      color: var(--text-main);
      border: 1px solid var(--border);
    }

    .btn-outline:hover {
      background: var(--bg-subtle);
    }

    .btn-primary {
      background: var(--primary);
      color: #ffffff;
      box-shadow: 0 4px 12px rgba(15, 23, 42, 0.18);
    }

    .btn-primary:hover {
      background: #1e293b;
      transform: translateY(-1px);
    }

    /* Main Invoice Sheet */
    .invoice-wrapper {
      max-width: 860px;
      margin: 0 auto;
      background: var(--bg-card);
      border-radius: var(--radius-lg);
      box-shadow: var(--shadow-card);
      overflow: hidden;
      border: 1px solid rgba(226, 232, 240, 0.8);
    }

    /* Header Banner */
    .invoice-header {
      background: linear-gradient(135deg, #0f172a 0%, #1e293b 100%);
      color: #ffffff;
      padding: 28px 24px;
      position: relative;
      overflow: hidden;
    }

    .invoice-header::after {
      content: "";
      position: absolute;
      top: -60px;
      right: -60px;
      width: 220px;
      height: 220px;
      background: radial-gradient(circle, rgba(5, 150, 105, 0.28) 0%, transparent 70%);
      pointer-events: none;
    }

    .header-top {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 16px;
      flex-wrap: wrap;
    }

    .brand-block {
      display: flex;
      align-items: center;
      gap: 12px;
    }

    .brand-logo {
      width: 46px;
      height: 46px;
      border-radius: 12px;
      background: linear-gradient(135deg, #10b981, #059669);
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 800;
      font-size: 20px;
      color: #fff;
      letter-spacing: -0.5px;
      flex-shrink: 0;
      box-shadow: 0 6px 16px rgba(16, 185, 129, 0.35);
    }

    .brand-info h1 {
      font-size: 20px;
      font-weight: 800;
      letter-spacing: -0.3px;
    }

    .brand-info p {
      font-size: 12px;
      color: #cbd5e1;
      margin-top: 2px;
    }

    .invoice-badge-box {
      text-align: right;
    }

    .invoice-title {
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 1.8px;
      color: #94a3b8;
      font-weight: 700;
    }

    .invoice-number {
      font-size: 18px;
      font-weight: 800;
      margin-top: 2px;
      letter-spacing: 0.3px;
    }

    .status-pill {
      display: inline-flex;
      align-items: center;
      gap: 6px;
      margin-top: 8px;
      padding: 4px 11px;
      border-radius: 999px;
      font-size: 11px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.6px;
      background: rgba(16, 185, 129, 0.16);
      color: #6ee7b7;
      border: 1px solid rgba(110, 231, 183, 0.3);
    }

    .status-dot {
      width: 7px;
      height: 7px;
      border-radius: 50%;
      background: #10b981;
    }

    /* Meta Strip */
    .meta-grid {
      display: grid;
      grid-template-columns: repeat(2, 1fr);
      gap: 12px;
      padding: 18px 24px;
      background: var(--bg-subtle);
      border-bottom: 1px solid var(--border);
    }

    .meta-item label {
      display: block;
      font-size: 11px;
      font-weight: 600;
      text-transform: uppercase;
      letter-spacing: 0.6px;
      color: var(--text-muted);
    }

    .meta-item span {
      display: block;
      font-size: 13.5px;
      font-weight: 700;
      color: var(--text-main);
      margin-top: 2px;
    }

    /* Billing & Shipping Addresses */
    .address-section {
      display: grid;
      grid-template-columns: 1fr;
      gap: 16px;
      padding: 22px 24px;
      border-bottom: 1px solid var(--border);
    }

    .address-card {
      background: #ffffff;
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      padding: 14px 16px;
    }

    .address-card h3 {
      font-size: 11px;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: var(--text-muted);
      font-weight: 700;
      margin-bottom: 6px;
      display: flex;
      align-items: center;
      gap: 6px;
    }

    .address-card .name {
      font-size: 15px;
      font-weight: 700;
      color: var(--text-main);
    }

    .address-card p {
      font-size: 13px;
      color: var(--text-muted);
      margin-top: 3px;
      line-height: 1.45;
    }

    /* Items Section - Mobile First Card Layout + Desktop Table */
    .items-section {
      padding: 22px 24px 10px 24px;
    }

    .section-heading {
      font-size: 12px;
      font-weight: 700;
      text-transform: uppercase;
      letter-spacing: 0.8px;
      color: var(--text-muted);
      margin-bottom: 14px;
    }

    /* Desktop Table Header (hidden on mobile) */
    .table-header {
      display: none;
    }

    .item-row {
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      padding: 14px;
      margin-bottom: 12px;
      background: #ffffff;
      transition: border-color 0.2s;
    }

    .item-top {
      display: flex;
      justify-content: space-between;
      align-items: flex-start;
      gap: 12px;
    }

    .item-info {
      display: flex;
      gap: 12px;
      align-items: flex-start;
    }

    .item-thumb {
      width: 44px;
      height: 44px;
      border-radius: 10px;
      background: var(--bg-subtle);
      border: 1px solid var(--border);
      display: flex;
      align-items: center;
      justify-content: center;
      font-size: 18px;
      flex-shrink: 0;
    }

    .item-name {
      font-size: 14px;
      font-weight: 700;
      color: var(--text-main);
    }

    .item-sku {
      font-size: 11.5px;
      color: var(--text-muted);
      margin-top: 2px;
    }

    .item-tag {
      display: inline-block;
      margin-top: 5px;
      font-size: 10.5px;
      font-weight: 700;
      padding: 2px 7px;
      border-radius: 6px;
      background: var(--accent-soft);
      color: var(--accent);
    }

    .item-total-mobile {
      text-align: right;
      flex-shrink: 0;
    }

    .item-total-mobile .final {
      font-size: 15px;
      font-weight: 800;
      color: var(--text-main);
    }

    .item-total-mobile .strike {
      font-size: 11.5px;
      color: var(--text-light);
      text-decoration: line-through;
    }

    .item-breakdown-mobile {
      margin-top: 12px;
      padding-top: 10px;
      border-top: 1px dashed var(--border);
      display: grid;
      grid-template-columns: repeat(3, 1fr);
      gap: 8px;
      font-size: 12px;
    }

    .item-breakdown-mobile div span {
      display: block;
      color: var(--text-muted);
      font-size: 11px;
    }

    .item-breakdown-mobile div strong {
      color: var(--text-main);
      font-weight: 700;
    }

    /* Desktop cell columns hidden on mobile */
    .col-price, .col-qty, .col-disc, .col-tax, .col-total {
      display: none;
    }

    /* Summary & Totals Section */
    .bottom-section {
      padding: 12px 24px 28px 24px;
      display: grid;
      grid-template-columns: 1fr;
      gap: 20px;
    }

    /* Coupon & Payment Info Box */
    .notes-box {
      display: flex;
      flex-direction: column;
      gap: 12px;
    }

    .coupon-applied-card {
      background: var(--accent-soft);
      border: 1px dashed var(--accent);
      border-radius: var(--radius-md);
      padding: 12px 14px;
      display: flex;
      align-items: center;
      justify-content: space-between;
      gap: 10px;
    }

    .coupon-left {
      display: flex;
      align-items: center;
      gap: 10px;
    }

    .coupon-icon {
      width: 34px;
      height: 34px;
      border-radius: 8px;
      background: var(--accent);
      color: #fff;
      display: flex;
      align-items: center;
      justify-content: center;
      font-weight: 800;
      font-size: 15px;
      flex-shrink: 0;
    }

    .coupon-left h4 {
      font-size: 13px;
      font-weight: 800;
      color: #065f46;
      letter-spacing: 0.3px;
    }

    .coupon-left p {
      font-size: 11.5px;
      color: #047857;
    }

    .coupon-amount {
      font-size: 14px;
      font-weight: 800;
      color: #065f46;
      white-space: nowrap;
    }

    .payment-card {
      background: var(--bg-subtle);
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      padding: 14px;
    }

    .payment-card h4 {
      font-size: 11.5px;
      text-transform: uppercase;
      letter-spacing: 0.7px;
      color: var(--text-muted);
      margin-bottom: 8px;
    }

    .payment-row {
      display: flex;
      justify-content: space-between;
      font-size: 12.5px;
      margin-bottom: 5px;
    }

    .payment-row span:first-child {
      color: var(--text-muted);
    }

    .payment-row span:last-child {
      font-weight: 700;
      color: var(--text-main);
    }

    /* Calculation Summary Card */
    .totals-card {
      background: var(--bg-subtle);
      border: 1px solid var(--border);
      border-radius: var(--radius-md);
      padding: 18px;
    }

    .calc-row {
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 13.5px;
      padding: 6px 0;
      color: var(--text-muted);
    }

    .calc-row strong {
      color: var(--text-main);
      font-weight: 700;
    }

    .calc-row.discount strong {
      color: var(--accent);
    }

    .total-savings-highlight {
      margin: 8px 0;
      padding: 8px 12px;
      background: #ecfdf5;
      border: 1px solid var(--accent-border);
      border-radius: 8px;
      display: flex;
      justify-content: space-between;
      align-items: center;
      font-size: 12.5px;
      font-weight: 700;
      color: #065f46;
    }

    .calc-divider {
      height: 1px;
      background: var(--border);
      margin: 10px 0;
    }

    .grand-total-row {
      display: flex;
      justify-content: space-between;
      align-items: baseline;
      padding-top: 4px;
    }

    .grand-total-row .label {
      font-size: 15px;
      font-weight: 800;
      color: var(--text-main);
    }

    .grand-total-row .sub-label {
      display: block;
      font-size: 11px;
      font-weight: 500;
      color: var(--text-muted);
    }

    .grand-total-row .amount {
      font-size: 24px;
      font-weight: 800;
      color: var(--primary);
      letter-spacing: -0.5px;
    }

    /* Footer */
    .invoice-footer {
      background: var(--bg-subtle);
      border-top: 1px solid var(--border);
      padding: 18px 24px;
      display: flex;
      flex-direction: column;
      gap: 8px;
      text-align: center;
      font-size: 12px;
      color: var(--text-muted);
    }

    .invoice-footer strong {
      color: var(--text-main);
    }

    /* =========================================
       TABLET & DESKTOP RESPONSIVE ENHANCEMENTS
       ========================================= */
    @media (min-width: 700px) {
      body {
        padding: 32px 20px;
      }

      .invoice-header {
        padding: 34px 36px;
      }

      .brand-info h1 {
        font-size: 23px;
      }

      .invoice-number {
        font-size: 22px;
      }

      .meta-grid {
        grid-template-columns: repeat(4, 1fr);
        padding: 20px 36px;
      }

      .address-section {
        grid-template-columns: 1fr 1fr;
        padding: 24px 36px;
      }

      .items-section {
        padding: 26px 36px 10px 36px;
      }

      /* Switch Item Cards into Sleek Desktop Table Grid */
      .table-header {
        display: grid;
        grid-template-columns: 2.6fr 0.9fr 0.6fr 0.9fr 0.8fr 1fr;
        gap: 10px;
        padding: 11px 16px;
        background: var(--bg-subtle);
        border: 1px solid var(--border);
        border-radius: 10px;
        font-size: 11px;
        font-weight: 700;
        text-transform: uppercase;
        letter-spacing: 0.6px;
        color: var(--text-muted);
        margin-bottom: 10px;
      }

      .table-header div:not(:first-child) {
        text-align: right;
      }

      .item-row {
        display: grid;
        grid-template-columns: 2.6fr 0.9fr 0.6fr 0.9fr 0.8fr 1fr;
        gap: 10px;
        align-items: center;
        padding: 14px 16px;
        margin-bottom: 8px;
      }

      .item-row:hover {
        border-color: #cbd5e1;
      }

      .item-total-mobile,
      .item-breakdown-mobile {
        display: none;
      }

      .col-price, .col-qty, .col-disc, .col-tax, .col-total {
        display: block;
        text-align: right;
        font-size: 13.5px;
      }

      .col-price {
        color: var(--text-muted);
        font-weight: 500;
      }

      .col-qty {
        font-weight: 700;
        color: var(--text-main);
      }

      .col-disc {
        color: var(--accent);
        font-weight: 600;
      }

      .col-tax {
        color: var(--text-muted);
        font-size: 12.5px;
      }

      .col-total {
        font-weight: 800;
        color: var(--text-main);
        font-size: 14px;
      }

      .bottom-section {
        grid-template-columns: 1.1fr 1fr;
        padding: 16px 36px 34px 36px;
        align-items: start;
      }

      .invoice-footer {
        flex-direction: row;
        justify-content: space-between;
        text-align: left;
        padding: 18px 36px;
      }
    }

    /* Clean Print Styling */
    @media print {
      body {
        background: #ffffff;
        padding: 0;
      }
      .action-bar {
        display: none;
      }
      .invoice-wrapper {
        box-shadow: none;
        border: none;
        border-radius: 0;
        max-width: 100%;
      }
    }
  </style>
</head>
<body>

  <!-- Action Bar -->
  <div class="action-bar">
    <span>Sales Invoice Preview</span>
    <div class="btn-group">
      <button class="btn btn-outline" onclick="window.print()">🖨️ Print</button>
      <button class="btn btn-primary" onclick="window.print()">⬇️ Download PDF</button>
    </div>
  </div>

  <!-- Invoice Container -->
  <div class="invoice-wrapper">

    <!-- Top Dark Brand Banner -->
    <header class="invoice-header">
      <div class="header-top">
        <div class="brand-block">
          <div class="brand-logo">PO</div>
          <div class="brand-info">
            <h1>PureOlaa Naturals</h1>
            <p>Mirpur-1, Dhaka-1216 • BIN: 004829104-0203</p>
            <p>support@pureolaa.com • +880 1712-794033</p>
          </div>
        </div>

        <div class="invoice-badge-box">
          <div class="invoice-title">Tax & Sales Invoice</div>
          <div class="invoice-number">#INV-2026-8492</div>
          <div class="status-pill">
            <span class="status-dot"></span> Paid in Full
          </div>
        </div>
      </div>
    </header>

    <!-- Meta Info Strip -->
    <section class="meta-grid">
      <div class="meta-item">
        <label>Invoice Date</label>
        <span>19 Nov, 2026</span>
      </div>
      <div class="meta-item">
        <label>Order ID</label>
        <span>#ORD-99412</span>
      </div>
      <div class="meta-item">
        <label>Payment Method</label>
        <span>bKash / SSLCommerz</span>
      </div>
      <div class="meta-item">
        <label>Delivery Courier</label>
        <span>Pathao Express</span>
      </div>
    </section>

    <!-- Customer & Shipping Addresses -->
    <section class="address-section">
      <div class="address-card">
        <h3>🧾 Billed To</h3>
        <div class="name">Tanvir Ahmed Chowdhury</div>
        <p>House 42, Road 07, Block C, Banani</p>
        <p>Dhaka-1213, Bangladesh</p>
        <p>📞 +880 1819-442210 • tanvir.c@example.com</p>
      </div>

      <div class="address-card">
        <h3>🚚 Shipped To</h3>
        <div class="name">Tanvir Ahmed (Office Desk)</div>
        <p>Level 6, Quantum Tower, Gulshan Avenue</p>
        <p>Dhaka-1212 • Tracking: <strong>PTH-8829104</strong></p>
        <p>Delivery Zone: Inside Dhaka (Same Day)</p>
      </div>
    </section>

    <!-- Line Items Section -->
    <section class="items-section">
      <div class="section-heading">Purchased Items (3)</div>

      <!-- Desktop Table Header -->
      <div class="table-header">
        <div>Item Description</div>
        <div>Unit Price</div>
        <div>Qty</div>
        <div>Item Disc.</div>
        <div>VAT (5%)</div>
        <div>Line Total</div>
      </div>

      <!-- Item 1 -->
      <div class="item-row">
        <div class="item-top">
          <div class="item-info">
            <div class="item-thumb">🍯</div>
            <div>
              <div class="item-name">Sundarbans Raw Wild Honey (1kg Jar)</div>
              <div class="item-sku">SKU: PO-HNY-1000 • Batch #B26-09</div>
              <span class="item-tag">10% Item Discount Applied</span>
            </div>
          </div>
          <!-- Mobile Price Summary -->
          <div class="item-total-mobile">
            <div class="final">৳2,268</div>
            <div class="strike">৳2,400</div>
          </div>
        </div>

        <!-- Mobile Breakdown -->
        <div class="item-breakdown-mobile">
          <div><span>Unit × Qty</span><strong>৳1,200 × 2</strong></div>
          <div><span>Item Disc.</span><strong style="color:var(--accent)">-৳240</strong></div>
          <div><span>VAT (5%)</span><strong>+৳108</strong></div>
        </div>

        <!-- Desktop Columns -->
        <div class="col-price">৳1,200.00</div>
        <div class="col-qty">2</div>
        <div class="col-disc">-৳240.00</div>
        <div class="col-tax">৳108.00</div>
        <div class="col-total">৳2,268.00</div>
      </div>

      <!-- Item 2 -->
      <div class="item-row">
        <div class="item-top">
          <div class="item-info">
            <div class="item-thumb">🧈</div>
            <div>
              <div class="item-name">Premium Pabna Danadar Cow Ghee (500g)</div>
              <div class="item-sku">SKU: PO-GHE-500 • Glass Jar</div>
              <span class="item-tag">Flat ৳100 Off</span>
            </div>
          </div>
          <div class="item-total-mobile">
            <div class="final">৳840</div>
            <div class="strike">৳900</div>
          </div>
        </div>

        <div class="item-breakdown-mobile">
          <div><span>Unit × Qty</span><strong>৳900 × 1</strong></div>
          <div><span>Item Disc.</span><strong style="color:var(--accent)">-৳100</strong></div>
          <div><span>VAT (5%)</span><strong>+৳40</strong></div>
        </div>

        <div class="col-price">৳900.00</div>
        <div class="col-qty">1</div>
        <div class="col-disc">-৳100.00</div>
        <div class="col-tax">৳40.00</div>
        <div class="col-total">৳840.00</div>
      </div>

      <!-- Item 3 -->
      <div class="item-row">
        <div class="item-top">
          <div class="item-info">
            <div class="item-thumb">🥥</div>
            <div>
              <div class="item-name">Cold-Pressed Extra Virgin Coconut Oil (500ml)</div>
              <div class="item-sku">SKU: PO-OIL-500 • Organic Certified</div>
            </div>
          </div>
          <div class="item-total-mobile">
            <div class="final">৳682.50</div>
          </div>
        </div>

        <div class="item-breakdown-mobile">
          <div><span>Unit × Qty</span><strong>৳650 × 1</strong></div>
          <div><span>Item Disc.</span><strong>৳0</strong></div>
          <div><span>VAT (5%)</span><strong>+৳32.50</strong></div>
        </div>

        <div class="col-price">৳650.00</div>
        <div class="col-qty">1</div>
        <div class="col-disc">৳0.00</div>
        <div class="col-tax">৳32.50</div>
        <div class="col-total">৳682.50</div>
      </div>
    </section>

    <!-- Bottom Summary: Coupon/Payment + Financial Totals -->
    <section class="bottom-section">

      <!-- Left: Applied Coupon & Transaction Info -->
      <div class="notes-box">
        <div class="coupon-applied-card">
          <div class="coupon-left">
            <div class="coupon-icon">%</div>
            <div>
              <h4>COUPON: WINTER200</h4>
              <p>Promo code applied on cart subtotal</p>
            </div>
          </div>
          <div class="coupon-amount">-৳200.00</div>
        </div>

        <div class="payment-card">
          <h4>Payment & Transaction Details</h4>
          <div class="payment-row">
            <span>Transaction ID</span>
            <span>TXN-98437219BK</span>
          </div>
          <div class="payment-row">
            <span>Payment Date</span>
            <span>19 Nov 2026, 04:42 PM</span>
          </div>
          <div class="payment-row">
            <span>Paid Amount</span>
            <span style="color: var(--accent);">৳3,650.50</span>
          </div>
          <div class="payment-row">
            <span>Due Balance</span>
            <span>৳0.00</span>
          </div>
        </div>
      </div>

      <!-- Right: Complete Price & Tax Calculation -->
      <div class="totals-card">
        <div class="calc-row">
          <span>Gross Subtotal (4 items)</span>
          <strong>৳3,950.00</strong>
        </div>

        <div class="calc-row discount">
          <span>Product-Level Discount</span>
          <strong>-৳340.00</strong>
        </div>

        <div class="calc-row discount">
          <span>Coupon Discount (WINTER200)</span>
          <strong>-৳200.00</strong>
        </div>

        <!-- Highlighted Total Discount Savings -->
        <div class="total-savings-highlight">
          <span>🎉 Total Discount Saved</span>
          <span>-৳540.00</span>
        </div>

        <div class="calc-row">
          <span>Net Taxable Amount</span>
          <strong>৳3,410.00</strong>
        </div>

        <div class="calc-row">
          <span>Total VAT / Tax (5%)</span>
          <strong>+৳180.50</strong>
        </div>

        <div class="calc-row">
          <span>Shipping Charge (Inside Dhaka)</span>
          <strong>+৳60.00</strong>
        </div>

        <div class="calc-divider"></div>

        <div class="grand-total-row">
          <div>
            <span class="label">Grand Total</span>
            <span class="sub-label">Inclusive of VAT & Shipping</span>
          </div>
          <div class="amount">৳3,650.50</div>
        </div>
      </div>

    </section>

    <!-- Footer -->
    <footer class="invoice-footer">
      <div>Thank you for shopping with <strong>PureOlaa Naturals</strong>!</div>
      <div>Computer-generated invoice • No signature required</div>
    </footer>

  </div>

</body>
</html>