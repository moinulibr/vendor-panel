@extends('report-dummy.report')
@section('title', 'Customer Ledger')
@section('page-title', 'Customer Ledger & Transaction History')
@section('page-subtitle', 'কাস্টমারের সম্পূর্ণ লেনদেন ইতিহাস')

@section('content')

{{-- ============ FILTER BAR ============ --}}
<div class="card mb-6">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="md:col-span-2">
            <label class="label">Customer</label>
            <select class="input">
                <option>👤 Rahim Ahmed — 01711000001</option>
                <option>👤 Sadia Khan — 01711000002</option>
                <option>👤 Karim Uddin — 01711000003</option>
                <option>👤 Nasrin Akter — 01711000004</option>
            </select>
        </div>
        <div>
            <label class="label">From</label>
            <input type="date" class="input" value="2024-11-01">
        </div>
        <div>
            <label class="label">To</label>
            <input type="date" class="input" value="2025-01-31">
        </div>
        <div class="flex items-end gap-2">
            <button class="btn btn-primary w-full justify-center">🔍 Load</button>
        </div>
    </div>
</div>

{{-- ============ CUSTOMER PROFILE HEADER ============ --}}
<div class="card mb-6 bg-gradient-to-br from-indigo-50 via-white to-white border-indigo-100">
    <div class="flex flex-col lg:flex-row lg:items-start justify-between gap-6">
        {{-- Left: Avatar + Info --}}
        <div class="flex items-start gap-4">
            <div class="w-16 h-16 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-700 text-white flex items-center justify-center text-2xl font-bold shadow-lg">
                রা
            </div>
            <div>
                <h3 class="text-xl font-bold text-gray-800">Rahim Ahmed</h3>
                <p class="text-sm text-gray-500">rahim@mail.com · +880 1711-000000</p>
                <p class="text-sm text-gray-500 mt-0.5">📍 Mirpur, Dhaka · 🎂 Member since Jan 2023</p>
                <div class="flex gap-2 mt-2">
                    <span class="badge badge-success">⭐ VIP Customer</span>
                    <span class="badge badge-info">🎁 1,250 Loyalty Points</span>
                    <span class="badge badge-warning">💳 Credit Limit: ৳ ২০,০০০</span>
                </div>
            </div>
        </div>

        {{-- Right: Financial Snapshot --}}
        <div class="grid grid-cols-2 md:grid-cols-4 gap-4 text-right">
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium">Total Purchase</p>
                <p class="text-lg font-bold text-gray-800 mt-1">৳ ২,৮৫,০০০</p>
                <p class="text-xs text-gray-400">৪২ orders</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium">Total Paid</p>
                <p class="text-lg font-bold text-green-600 mt-1">৳ ২,৭৯,৫০০</p>
                <p class="text-xs text-gray-400">৩৮ payments</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium">Current Due</p>
                <p class="text-lg font-bold text-red-600 mt-1">৳ ৫,৫০০</p>
                <p class="text-xs text-red-500">Next due: Feb 05</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium">Total Returned</p>
                <p class="text-lg font-bold text-orange-600 mt-1">৳ ৪,২০০</p>
                <p class="text-xs text-gray-400">৩ returns</p>
            </div>
        </div>
    </div>
</div>

{{-- ============ DUE ALERT ============ --}}
<div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6 flex items-start gap-3">
    <div class="text-2xl">⏰</div>
    <div class="flex-1">
        <h3 class="font-semibold text-yellow-800">পরবর্তী Due Payment: ৫ ফেব্রুয়ারি, ২০২৫</h3>
        <p class="text-sm text-yellow-700 mt-0.5">৳ ৫,৫০০ বাকি আছে — পরিশোধের ৫ দিন বাকি</p>
    </div>
    <button class="btn btn-primary">💰 Receive Payment</button>
</div>

{{-- ============ TYPE FILTER TABS ============ --}}
<div class="card mb-6">
    <div class="flex flex-wrap gap-2 border-b border-gray-100 pb-3 mb-4">
        <button class="px-3 py-1.5 rounded-lg text-sm font-medium bg-indigo-600 text-white">All (67)</button>
        <button class="px-3 py-1.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100">🛒 Purchase (42)</button>
        <button class="px-3 py-1.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100">💰 Payment (38)</button>
        <button class="px-3 py-1.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100">↩️ Return (3)</button>
        <button class="px-3 py-1.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100">💸 Refund (2)</button>
        <button class="px-3 py-1.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100">🔄 Exchange (1)</button>
        <button class="px-3 py-1.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100">⚖️ Adjustment (4)</button>
        <button class="px-3 py-1.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100">✏️ Edit History (6)</button>
        <button class="px-3 py-1.5 rounded-lg text-sm font-medium text-gray-600 hover:bg-gray-100">🎁 Loyalty (5)</button>
    </div>

    <div class="flex flex-wrap items-center gap-3">
        <div class="flex-1 min-w-[200px]">
            <input type="text" placeholder="🔍 Search by invoice, reference, amount..." class="input">
        </div>
        <select class="input max-w-xs">
            <option>Sort: Newest first</option>
            <option>Sort: Oldest first</option>
            <option>Sort: Highest amount</option>
        </select>
        <button class="btn btn-outline">📅 Date Filter</button>
        <button class="btn btn-outline">⬇️ Export</button>
        <button class="btn btn-outline">🖨️ Print</button>
    </div>
</div>

{{-- ============ SUMMARY CARDS ============ --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-4 mb-6">
    <div class="stat-card border-l-4 border-indigo-500">
        <p class="text-xs text-gray-500 uppercase font-medium">Opening Balance</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">৳ ৩,২০০</p>
        <p class="text-xs text-gray-400 mt-1">Nov 01, 2024</p>
    </div>
    <div class="stat-card border-l-4 border-blue-500">
        <p class="text-xs text-gray-500 uppercase font-medium">Total Purchase</p>
        <p class="text-2xl font-bold text-blue-600 mt-1">৳ ৮৫,০০০</p>
        <p class="text-xs text-gray-400 mt-1">42 orders</p>
    </div>
    <div class="stat-card border-l-4 border-green-500">
        <p class="text-xs text-gray-500 uppercase font-medium">Total Paid</p>
        <p class="text-2xl font-bold text-green-600 mt-1">৳ ৮২,৭০০</p>
        <p class="text-xs text-gray-400 mt-1">38 payments</p>
    </div>
    <div class="stat-card border-l-4 border-orange-500">
        <p class="text-xs text-gray-500 uppercase font-medium">Returns/Refunds</p>
        <p class="text-2xl font-bold text-orange-600 mt-1">৳ ৪,২০০</p>
        <p class="text-xs text-gray-400 mt-1">3 returns · 2 refunds</p>
    </div>
    <div class="stat-card border-l-4 border-red-500 bg-red-50">
        <p class="text-xs text-red-700 uppercase font-medium">Closing Balance</p>
        <p class="text-2xl font-bold text-red-700 mt-1">৳ ৫,৫০০</p>
        <p class="text-xs text-red-500 mt-1">Due on Feb 05</p>
    </div>
</div>

{{-- ============ LEDGER TIMELINE + TABLE ============ --}}
<div class="card">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-gray-800">📒 Complete Transaction History</h3>
        <div class="flex gap-2 text-sm">
            <button class="px-3 py-1 rounded-lg border bg-indigo-50 text-indigo-600">Table</button>
            <button class="px-3 py-1 rounded-lg border text-gray-500">Timeline</button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table-report">
            <thead>
                <tr>
                    <th class="w-36">Date & Time</th>
                    <th>Type</th>
                    <th>Particulars / Details</th>
                    <th>Reference</th>
                    <th class="text-right">Debit (In)</th>
                    <th class="text-right">Credit (Out)</th>
                    <th class="text-right w-32">Balance</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>

                {{-- OPENING --}}
                <tr class="bg-gray-50 font-semibold">
                    <td>Nov 01, 2024</td>
                    <td><span class="badge badge-info">Opening</span></td>
                    <td class="text-gray-600" colspan="2">Opening Balance carried forward</td>
                    <td class="text-right">—</td>
                    <td class="text-right">—</td>
                    <td class="text-right text-gray-700">৳ ৩,২০০</td>
                    <td></td>
                </tr>

                {{-- 1. PURCHASE (POS) --}}
                <tr>
                    <td>
                        <div class="text-sm">Nov 03, 2024</div>
                        <div class="text-xs text-gray-500">10:30 AM</div>
                    </td>
                    <td>
                        <span class="badge badge-info">🛒 POS Purchase</span>
                    </td>
                    <td>
                        <div class="font-medium">Wireless Mouse ×2, Cable ×1</div>
                        <div class="text-xs text-gray-500">Counter: #1 · Cashier: Rahim</div>
                    </td>
                    <td class="font-mono text-xs">INV-1001</td>
                    <td class="text-right text-green-600">—</td>
                    <td class="text-right text-red-600">৳ ১,৪৫০</td>
                    <td class="text-right font-medium">৳ ৪,৬৫০</td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-xs">View</button>
                    </td>
                </tr>

                {{-- 2. FULL PAYMENT --}}
                <tr class="bg-green-50/40">
                    <td>
                        <div class="text-sm">Nov 03, 2024</div>
                        <div class="text-xs text-gray-500">10:31 AM</div>
                    </td>
                    <td>
                        <span class="badge badge-success">💰 Full Payment</span>
                    </td>
                    <td>
                        <div class="font-medium">Paid at counter — Cash</div>
                        <div class="text-xs text-gray-500">Received by: Rahim</div>
                    </td>
                    <td class="font-mono text-xs">PAY-0451</td>
                    <td class="text-right text-green-600">৳ ১,৪৫০</td>
                    <td class="text-right text-red-600">—</td>
                    <td class="text-right font-medium">৳ ৩,২০০</td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-xs">Receipt</button>
                    </td>
                </tr>

                {{-- 3. PURCHASE (CREDIT - Online) --}}
                <tr>
                    <td>
                        <div class="text-sm">Nov 08, 2024</div>
                        <div class="text-xs text-gray-500">02:15 PM</div>
                    </td>
                    <td>
                        <span class="badge badge-warning">🛒 Online Order</span>
                    </td>
                    <td>
                        <div class="font-medium">Bluetooth Speaker ×2</div>
                        <div class="text-xs text-gray-500">Vendor: TechZone BD · Channel: Website</div>
                        <div class="text-xs text-gray-500">🚚 Shipping: Home Delivery</div>
                    </td>
                    <td class="font-mono text-xs">INV-1002</td>
                    <td class="text-right text-green-600">—</td>
                    <td class="text-right text-red-600">৳ ৫,২০০</td>
                    <td class="text-right font-medium">৳ ৮,৪০০</td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-xs">View</button>
                    </td>
                </tr>

                {{-- 4. DUE DATE UPDATE (audit trail) --}}
                <tr class="bg-purple-50/30">
                    <td>
                        <div class="text-sm">Nov 09, 2024</div>
                        <div class="text-xs text-gray-500">09:00 AM</div>
                    </td>
                    <td>
                        <span class="badge" style="background:#ede9fe;color:#6d28d9">📅 Due Date Update</span>
                    </td>
                    <td>
                        <div class="font-medium">Next payment date set</div>
                        <div class="text-xs text-gray-500">Old: <s>Nov 20</s> → New: <b>Dec 05, 2024</b></div>
                        <div class="text-xs text-gray-500">Reason: Customer requested extension</div>
                    </td>
                    <td class="font-mono text-xs">INV-1002</td>
                    <td class="text-right">—</td>
                    <td class="text-right">—</td>
                    <td class="text-right font-medium">৳ ৮,৪০০</td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-xs">Audit</button>
                    </td>
                </tr>

                {{-- 5. PARTIAL PAYMENT --}}
                <tr class="bg-green-50/40">
                    <td>
                        <div class="text-sm">Dec 05, 2024</div>
                        <div class="text-xs text-gray-500">11:20 AM</div>
                    </td>
                    <td>
                        <span class="badge badge-success">💰 Partial Payment</span>
                    </td>
                    <td>
                        <div class="font-medium">Received ৳ ৫,০০০ / ৳ ৮,৪০০</div>
                        <div class="text-xs text-gray-500">Method: bKash · Txn: BK8821009</div>
                        <div class="text-xs text-orange-600">Remaining ৳ ৩,৪০০ · Next due: Dec 20</div>
                    </td>
                    <td class="font-mono text-xs">PAY-0465</td>
                    <td class="text-right text-green-600">৳ ৫,০০০</td>
                    <td class="text-right text-red-600">—</td>
                    <td class="text-right font-medium">৳ ৩,৪০০</td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-xs">Receipt</button>
                    </td>
                </tr>

                {{-- 6. PURCHASE (New) --}}
                <tr>
                    <td>
                        <div class="text-sm">Dec 12, 2024</div>
                        <div class="text-xs text-gray-500">04:45 PM</div>
                    </td>
                    <td>
                        <span class="badge badge-info">🛒 POS Purchase</span>
                    </td>
                    <td>
                        <div class="font-medium">Cotton T-Shirt ×3</div>
                        <div class="text-xs text-gray-500">Counter: #2 · Cashier: Karim</div>
                    </td>
                    <td class="font-mono text-xs">INV-1018</td>
                    <td class="text-right text-green-600">—</td>
                    <td class="text-right text-red-600">৳ ১,৫০০</td>
                    <td class="text-right font-medium">৳ ৪,৯০০</td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-xs">View</button>
                    </td>
                </tr>

                {{-- 7. RETURN --}}
                <tr class="bg-orange-50/40">
                    <td>
                        <div class="text-sm">Dec 18, 2024</div>
                        <div class="text-xs text-gray-500">11:00 AM</div>
                    </td>
                    <td>
                        <span class="badge" style="background:#ffedd5;color:#c2410c">↩️ Return</span>
                    </td>
                    <td>
                        <div class="font-medium">Bluetooth Speaker ×1 returned</div>
                        <div class="text-xs text-gray-500">Reason: Damaged on arrival</div>
                        <div class="text-xs text-gray-500">Condition: Working · Restocked</div>
                    </td>
                    <td class="font-mono text-xs">RET-0124</td>
                    <td class="text-right text-green-600">৳ ২,৬০০</td>
                    <td class="text-right text-red-600">—</td>
                    <td class="text-right font-medium">৳ ২,৩০০</td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-xs">View</button>
                    </td>
                </tr>

                {{-- 8. REFUND (Cash) --}}
                <tr class="bg-red-50/40">
                    <td>
                        <div class="text-sm">Dec 18, 2024</div>
                        <div class="text-xs text-gray-500">11:05 AM</div>
                    </td>
                    <td>
                        <span class="badge badge-danger">💸 Cash Refund</span>
                    </td>
                    <td>
                        <div class="font-medium">Cash refund for returned item</div>
                        <div class="text-xs text-gray-500">Refund method: Cash · Given by: Manager</div>
                        <div class="text-xs text-gray-500">Against return: RET-0124</div>
                    </td>
                    <td class="font-mono text-xs">REF-0031</td>
                    <td class="text-right text-green-600">—</td>
                    <td class="text-right text-red-600">৳ ২,৬০০</td>
                    <td class="text-right font-medium">৳ ৪,৯০০</td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-xs">Receipt</button>
                    </td>
                </tr>

                {{-- 9. EXCHANGE --}}
                <tr class="bg-blue-50/40">
                    <td>
                        <div class="text-sm">Dec 20, 2024</div>
                        <div class="text-xs text-gray-500">03:15 PM</div>
                    </td>
                    <td>
                        <span class="badge badge-info">🔄 Exchange</span>
                    </td>
                    <td>
                        <div class="font-medium">T-Shirt (M) → T-Shirt (L)</div>
                        <div class="text-xs text-gray-500">Price diff: ৳ ০ · No adjustment</div>
                        <div class="text-xs text-gray-500">Against: INV-1018</div>
                    </td>
                    <td class="font-mono text-xs">EXC-0012</td>
                    <td class="text-right">—</td>
                    <td class="text-right">—</td>
                    <td class="text-right font-medium">৳ ৪,৯০০</td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-xs">View</button>
                    </td>
                </tr>

                {{-- 10. DUE PAYMENT --}}
                <tr class="bg-green-50/40">
                    <td>
                        <div class="text-sm">Dec 25, 2024</div>
                        <div class="text-xs text-gray-500">09:30 AM</div>
                    </td>
                    <td>
                        <span class="badge badge-success">💰 Due Payment</span>
                    </td>
                    <td>
                        <div class="font-medium">Old due cleared</div>
                        <div class="text-xs text-gray-500">Method: Card · Txn: CD7789</div>
                    </td>
                    <td class="font-mono text-xs">PAY-0489</td>
                    <td class="text-right text-green-600">৳ ৪,৯০০</td>
                    <td class="text-right text-red-600">—</td>
                    <td class="text-right font-medium">৳ ০</td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-xs">Receipt</button>
                    </td>
                </tr>

                {{-- 11. ADVANCE PAYMENT --}}
                <tr class="bg-green-50/40">
                    <td>
                        <div class="text-sm">Jan 03, 2025</div>
                        <div class="text-xs text-gray-500">10:15 AM</div>
                    </td>
                    <td>
                        <span class="badge badge-success">💰 Advance Payment</span>
                    </td>
                    <td>
                        <div class="font-medium">Advance for future orders</div>
                        <div class="text-xs text-gray-500">Method: bKash</div>
                    </td>
                    <td class="font-mono text-xs">PAY-0501</td>
                    <td class="text-right text-green-600">৳ ৩,০০০</td>
                    <td class="text-right text-red-600">—</td>
                    <td class="text-right font-medium text-green-700">- ৳ ৩,০০০</td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-xs">Receipt</button>
                    </td>
                </tr>

                {{-- 12. PURCHASE --}}
                <tr>
                    <td>
                        <div class="text-sm">Jan 08, 2025</div>
                        <div class="text-xs text-gray-500">01:20 PM</div>
                    </td>
                    <td>
                        <span class="badge badge-info">🛒 POS Purchase</span>
                    </td>
                    <td>
                        <div class="font-medium">Phone Case ×5, Cable ×2</div>
                        <div class="text-xs text-gray-500">Counter: #1</div>
                    </td>
                    <td class="font-mono text-xs">INV-1201</td>
                    <td class="text-right text-green-600">—</td>
                    <td class="text-right text-red-600">৳ ২,২০০</td>
                    <td class="text-right font-medium text-green-700">- ৳ ৮০০</td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-xs">View</button>
                    </td>
                </tr>

                {{-- 13. LOYALTY EARN --}}
                <tr class="bg-yellow-50/30">
                    <td>
                        <div class="text-sm">Jan 08, 2025</div>
                        <div class="text-xs text-gray-500">01:20 PM</div>
                    </td>
                    <td>
                        <span class="badge" style="background:#fef3c7;color:#b45309">🎁 Loyalty Earn</span>
                    </td>
                    <td>
                        <div class="font-medium">+220 points earned</div>
                        <div class="text-xs text-gray-500">1 point per ৳ 10 spent</div>
                        <div class="text-xs text-gray-500">Against: INV-1201</div>
                    </td>
                    <td class="font-mono text-xs">LP-2201</td>
                    <td class="text-right">—</td>
                    <td class="text-right">—</td>
                    <td class="text-right font-medium">—</td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-xs">View</button>
                    </td>
                </tr>

                {{-- 14. LOYALTY REDEEM --}}
                <tr class="bg-yellow-50/30">
                    <td>
                        <div class="text-sm">Jan 15, 2025</div>
                        <div class="text-xs text-gray-500">02:45 PM</div>
                    </td>
                    <td>
                        <span class="badge" style="background:#fef3c7;color:#b45309">🎁 Loyalty Redeem</span>
                    </td>
                    <td>
                        <div class="font-medium">-1,000 points redeemed</div>
                        <div class="text-xs text-gray-500">Applied as ৳ 100 discount</div>
                    </td>
                    <td class="font-mono text-xs">LP-2210</td>
                    <td class="text-right text-green-600">৳ ১০০</td>
                    <td class="text-right">—</td>
                    <td class="text-right font-medium text-green-700">- ৳ ৭০০</td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-xs">View</button>
                    </td>
                </tr>

                {{-- 15. DISCOUNT / ADJUSTMENT --}}
                <tr class="bg-purple-50/30">
                    <td>
                        <div class="text-sm">Jan 20, 2025</div>
                        <div class="text-xs text-gray-500">03:00 PM</div>
                    </td>
                    <td>
                        <span class="badge" style="background:#ede9fe;color:#6d28d9">⚖️ Adjustment</span>
                    </td>
                    <td>
                        <div class="font-medium">Goodwill discount</div>
                        <div class="text-xs text-gray-500">Reason: Delivery delay compensation</div>
                        <div class="text-xs text-gray-500">Approved by: Manager</div>
                    </td>
                    <td class="font-mono text-xs">ADJ-0091</td>
                    <td class="text-right text-green-600">৳ ২৫০</td>
                    <td class="text-right text-red-600">—</td>
                    <td class="text-right font-medium text-green-700">- ৳ ৯৫০</td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-xs">View</button>
                    </td>
                </tr>

                {{-- 16. EDIT / AUDIT --}}
                <tr class="bg-gray-100/60">
                    <td>
                        <div class="text-sm">Jan 22, 2025</div>
                        <div class="text-xs text-gray-500">11:45 AM</div>
                    </td>
                    <td>
                        <span class="badge" style="background:#e5e7eb;color:#374151">✏️ Edit / Correction</span>
                    </td>
                    <td>
                        <div class="font-medium">Invoice INV-1201 edited</div>
                        <div class="text-xs text-gray-500">
                            Price changed: <s>৳ 220</s> → ৳ 200 (Phone Case)
                        </div>
                        <div class="text-xs text-gray-500">
                            Discount: <s>৳ 0</s> → ৳ 50 · Ref: #EDT-0044
                        </div>
                        <div class="text-xs text-gray-500">Edited by: Admin</div>
                    </td>
                    <td class="font-mono text-xs">EDT-0044</td>
                    <td class="text-right text-green-600">৳ ১৫০</td>
                    <td class="text-right text-red-600">—</td>
                    <td class="text-right font-medium text-green-700">- ৳ ১,১০০</td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-xs">Diff</button>
                    </td>
                </tr>

                {{-- 17. WALLET TOPUP --}}
                <tr class="bg-cyan-50/40">
                    <td>
                        <div class="text-sm">Jan 25, 2025</div>
                        <div class="text-xs text-gray-500">10:00 AM</div>
                    </td>
                    <td>
                        <span class="badge" style="background:#cffafe;color:#0e7490">👛 Wallet Top-up</span>
                    </td>
                    <td>
                        <div class="font-medium">Wallet balance added</div>
                        <div class="text-xs text-gray-500">Method: bKash</div>
                    </td>
                    <td class="font-mono text-xs">WLT-0102</td>
                    <td class="text-right text-green-600">৳ ৫,০০০</td>
                    <td class="text-right text-red-600">—</td>
                    <td class="text-right font-medium text-green-700">- ৳ ৬,১০০</td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-xs">View</button>
                    </td>
                </tr>

                {{-- 18. ORDER CANCEL --}}
                <tr class="bg-red-50/30">
                    <td>
                        <div class="text-sm">Jan 27, 2025</div>
                        <div class="text-xs text-gray-500">05:30 PM</div>
                    </td>
                    <td>
                        <span class="badge badge-danger">❌ Order Cancelled</span>
                    </td>
                    <td>
                        <div class="font-medium">Order INV-1215 cancelled</div>
                        <div class="text-xs text-gray-500">Reason: Customer changed mind</div>
                        <div class="text-xs text-gray-500">No charge applied</div>
                    </td>
                    <td class="font-mono text-xs">INV-1215</td>
                    <td class="text-right">—</td>
                    <td class="text-right">—</td>
                    <td class="text-right font-medium text-green-700">- ৳ ৬,১০০</td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-xs">View</button>
                    </td>
                </tr>

                {{-- 19. PURCHASE (final) --}}
                <tr>
                    <td>
                        <div class="text-sm">Jan 28, 2025</div>
                        <div class="text-xs text-gray-500">12:30 PM</div>
                    </td>
                    <td>
                        <span class="badge badge-warning">🛒 Online Order</span>
                    </td>
                    <td>
                        <div class="font-medium">Winter Jacket ×1</div>
                        <div class="text-xs text-gray-500">Vendor: Fashion Hub · Website</div>
                    </td>
                    <td class="font-mono text-xs">INV-1004</td>
                    <td class="text-right text-green-600">—</td>
                    <td class="text-right text-red-600">৳ ২,০০০</td>
                    <td class="text-right font-medium text-green-700">- ৳ ৪,১০০</td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-xs">View</button>
                    </td>
                </tr>

                {{-- 20. WALLET SPEND --}}
                <tr class="bg-cyan-50/40">
                    <td>
                        <div class="text-sm">Jan 28, 2025</div>
                        <div class="text-xs text-gray-500">12:30 PM</div>
                    </td>
                    <td>
                        <span class="badge" style="background:#cffafe;color:#0e7490">👛 Wallet Spend</span>
                    </td>
                    <td>
                        <div class="font-medium">Paid from wallet</div>
                        <div class="text-xs text-gray-500">Against: INV-1004</div>
                    </td>
                    <td class="font-mono text-xs">WLT-0110</td>
                    <td class="text-right text-green-600">৳ ২,০০০</td>
                    <td class="text-right text-red-600">—</td>
                    <td class="text-right font-medium">৳ ২,১০০</td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-xs">View</button>
                    </td>
                </tr>

                {{-- 21. DUE PAYMENT --}}
                <tr class="bg-green-50/40">
                    <td>
                        <div class="text-sm">Jan 30, 2025</div>
                        <div class="text-xs text-gray-500">03:00 PM</div>
                    </td>
                    <td>
                        <span class="badge badge-success">💰 Due Payment</span>
                    </td>
                    <td>
                        <div class="font-medium">Partial due settled</div>
                        <div class="text-xs text-gray-500">Method: Cash</div>
                    </td>
                    <td class="font-mono text-xs">PAY-0520</td>
                    <td class="text-right text-green-600">৳ ৫০০</td>
                    <td class="text-right text-red-600">—</td>
                    <td class="text-right font-medium">৳ ১,৬০০</td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-xs">Receipt</button>
                    </td>
                </tr>

                {{-- 22. CHEQUE BOUNCE --}}
                <tr class="bg-red-100/50">
                    <td>
                        <div class="text-sm">Jan 31, 2025</div>
                        <div class="text-xs text-gray-500">10:00 AM</div>
                    </td>
                    <td>
                        <span class="badge badge-danger">⚠️ Cheque Bounced</span>
                    </td>
                    <td>
                        <div class="font-medium">Cheque #001234 bounced</div>
                        <div class="text-xs text-gray-500">Bank: City Bank · Reason: Insufficient funds</div>
                        <div class="text-xs text-red-600 font-medium">Due restored: +৳ ৩,৯০০</div>
                    </td>
                    <td class="font-mono text-xs">CHQ-001234</td>
                    <td class="text-right text-green-600">—</td>
                    <td class="text-right text-red-600">৳ ৩,৯০০</td>
                    <td class="text-right font-medium text-red-700">৳ ৫,৫০০</td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-xs">View</button>
                    </td>
                </tr>

                {{-- 23. NEXT DUE SCHEDULE --}}
                <tr class="bg-yellow-50/50 border-t-2 border-yellow-300">
                    <td>
                        <div class="text-sm font-semibold">Feb 05, 2025</div>
                        <div class="text-xs text-yellow-700">(upcoming)</div>
                    </td>
                    <td>
                        <span class="badge badge-warning">📅 Next Due</span>
                    </td>
                    <td>
                        <div class="font-medium">Next scheduled payment</div>
                        <div class="text-xs text-gray-500">Amount: ৳ ৫,৫০০</div>
                        <div class="text-xs text-gray-500">Reminder: SMS + Email</div>
                    </td>
                    <td class="font-mono text-xs">—</td>
                    <td class="text-right">—</td>
                    <td class="text-right">—</td>
                    <td class="text-right font-medium text-yellow-700">৳ ৫,৫০০</td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-xs">Notify</button>
                    </td>
                </tr>
            </tbody>

            <tfoot>
                <tr class="bg-gray-100 font-semibold">
                    <td colspan="4" class="px-4 py-3 text-right">Period Total:</td>
                    <td class="px-4 py-3 text-right text-green-700">৳ ৮৭,৪০০</td>
                    <td class="px-4 py-3 text-right text-red-700">৳ ৮৫,১০০</td>
                    <td class="px-4 py-3 text-right">—</td>
                    <td></td>
                </tr>
                <tr class="bg-indigo-50 font-bold text-indigo-800">
                    <td colspan="6" class="px-4 py-3 text-right">Closing Balance (Due):</td>
                    <td class="px-4 py-3 text-right">৳ ৫,৫০০</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

{{-- ============ QUICK ACTIONS ============ --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mt-6">
    <button class="card hover:shadow-md text-left">
        <div class="text-3xl mb-2">💰</div>
        <p class="font-semibold text-gray-800">Receive Payment</p>
        <p class="text-xs text-gray-500">নতুন পেমেন্ট রেকর্ড করুন</p>
    </button>
    <button class="card hover:shadow-md text-left">
        <div class="text-3xl mb-2">🛒</div>
        <p class="font-semibold text-gray-800">New Order</p>
        <p class="text-xs text-gray-500">এই কাস্টমারের জন্য অর্ডার</p>
    </button>
    <button class="card hover:shadow-md text-left">
        <div class="text-3xl mb-2">📅</div>
        <p class="font-semibold text-gray-800">Update Due Date</p>
        <p class="text-xs text-gray-500">পরের পেমেন্টের তারিখ বদল</p>
    </button>
    <button class="card hover:shadow-md text-left">
        <div class="text-3xl mb-2">📧</div>
        <p class="font-semibold text-gray-800">Send Statement</p>
        <p class="text-xs text-gray-500">লেজার স্টেটমেন্ট পাঠান</p>
    </button>
</div>

@endsection