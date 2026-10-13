@extends('report-dummy.report')
@section('title', 'Journal Entries')
@section('page-title', 'Journal Entries')
@section('page-subtitle', 'সব মডিউলের কেন্দ্রীয় হিসাব — double-entry')

@section('content')

{{-- ============ FILTER BAR ============ --}}
<div class="card !rounded-2xl mb-6">
    <div class="grid grid-cols-1 md:grid-cols-6 gap-3 items-end">
        <div>
            <label class="label">From</label>
            <input type="date" class="input" value="2025-01-01">
        </div>
        <div>
            <label class="label">To</label>
            <input type="date" class="input" value="2025-01-31">
        </div>
        <div>
            <label class="label">Source Module</label>
            <select class="input">
                <option>All Modules</option>
                <option>POS Sale</option>
                <option>Online Order</option>
                <option>Purchase</option>
                <option>Vendor Payout</option>
                <option>Customer Payment</option>
                <option>Return / Refund</option>
                <option>Expense</option>
                <option>Manual Entry</option>
            </select>
        </div>
        <div>
            <label class="label">Account</label>
            <select class="input">
                <option>All Accounts</option>
                <option>1010 — Cash</option>
                <option>1020 — Bank</option>
                <option>1100 — Accounts Receivable</option>
                <option>2010 — Accounts Payable</option>
                <option>4010 — Sales Revenue</option>
            </select>
        </div>
        <div>
            <label class="label">Status</label>
            <select class="input">
                <option>All</option>
                <option>Posted</option>
                <option>Draft</option>
                <option>Reversed</option>
            </select>
        </div>
        <button class="btn btn-primary w-full justify-center !py-2.5">🔍 Filter</button>
    </div>
</div>

{{-- ============ TRIAL BALANCE HERO ============ --}}
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-900 text-white p-6 mb-6 shadow-xl">
    <div class="absolute -right-24 -top-24 w-72 h-72 bg-indigo-500/20 rounded-full blur-3xl"></div>
    <div class="absolute -left-24 -bottom-24 w-72 h-72 bg-emerald-500/20 rounded-full blur-3xl"></div>

    <div class="relative">
        <div class="flex flex-col lg:flex-row justify-between gap-6">
            {{-- Left: Balance Status --}}
            <div class="flex-1">
                <div class="flex items-center gap-3 mb-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-semibold">
                        <svg class="w-3 h-3" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                            <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        Balanced
                    </span>
                    <span class="text-slate-400 text-xs">Period: 01 – 31 January 2025</span>
                </div>
                <h2 class="text-2xl font-bold">Trial Balance — Total Debits = Total Credits</h2>
                <p class="text-slate-300 text-sm mt-1">১৮৫টি journal entries · সব balanced, কোনো discrepancy নেই</p>

                <div class="grid grid-cols-3 gap-6 mt-6">
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Total Debits</p>
                        <p class="text-2xl font-extrabold num mt-1 text-blue-300">৳ ২৪,৮৫,৩৪০</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Total Credits</p>
                        <p class="text-2xl font-extrabold num mt-1 text-rose-300">৳ ২৪,৮৫,৩৪০</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Difference</p>
                        <p class="text-2xl font-extrabold num mt-1 text-emerald-400">৳ ০</p>
                    </div>
                </div>
            </div>

            {{-- Right: Quick Actions --}}
            <div class="lg:min-w-[280px] space-y-3">
                <div class="rounded-xl bg-white/5 backdrop-blur-sm border border-white/10 p-4">
                    <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Auto-Posted</p>
                    <p class="text-2xl font-extrabold num mt-1">১৮৩</p>
                    <p class="text-xs text-slate-400 mt-1">POS, Inventory, Vendor, Payout</p>
                </div>
                <div class="rounded-xl bg-white/5 backdrop-blur-sm border border-white/10 p-4">
                    <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Manual Entries</p>
                    <p class="text-2xl font-extrabold num mt-1">২</p>
                    <p class="text-xs text-slate-400 mt-1">Adjustments</p>
                </div>
                <button class="w-full py-2.5 rounded-lg bg-white text-slate-900 font-semibold text-sm hover:bg-slate-100 transition">
                    + New Journal Entry
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ============ TOP STATS ============ --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="rounded-2xl bg-white ring-1 ring-gray-900/5 p-4 shadow-[0_1px_2px_rgba(16,24,40,0.04),0_8px_24px_-12px_rgba(16,24,40,0.08)]">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-7 h-7 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm">📥</div>
            <p class="text-[11px] font-semibold text-gray-500 uppercase">Receivable Impact</p>
        </div>
        <p class="text-2xl font-extrabold text-gray-800 num mt-1">৳ ৪,২৫,০০০</p>
        <p class="text-[10px] text-gray-400 mt-0.5">Dr. A/R increase</p>
    </div>
    <div class="rounded-2xl bg-white ring-1 ring-gray-900/5 p-4 shadow-[0_1px_2px_rgba(16,24,40,0.04),0_8px_24px_-12px_rgba(16,24,40,0.08)]">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-7 h-7 rounded-lg bg-rose-100 text-rose-600 flex items-center justify-center text-sm">📤</div>
            <p class="text-[11px] font-semibold text-gray-500 uppercase">Payable Impact</p>
        </div>
        <p class="text-2xl font-extrabold text-gray-800 num mt-1">৳ ৩,১০,০০০</p>
        <p class="text-[10px] text-gray-400 mt-0.5">Cr. A/P increase</p>
    </div>
    <div class="rounded-2xl bg-white ring-1 ring-gray-900/5 p-4 shadow-[0_1px_2px_rgba(16,24,40,0.04),0_8px_24px_-12px_rgba(16,24,40,0.08)]">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm">📈</div>
            <p class="text-[11px] font-semibold text-gray-500 uppercase">Revenue Posted</p>
        </div>
        <p class="text-2xl font-extrabold text-gray-800 num mt-1">৳ ১২,৫০,০০০</p>
        <p class="text-[10px] text-gray-400 mt-0.5">Sales + Commission</p>
    </div>
    <div class="rounded-2xl bg-white ring-1 ring-gray-900/5 p-4 shadow-[0_1px_2px_rgba(16,24,40,0.04),0_8px_24px_-12px_rgba(16,24,40,0.08)]">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-7 h-7 rounded-lg bg-orange-100 text-orange-600 flex items-center justify-center text-sm">📉</div>
            <p class="text-[11px] font-semibold text-gray-500 uppercase">Expense Posted</p>
        </div>
        <p class="text-2xl font-extrabold text-gray-800 num mt-1">৳ ২,১০,০০০</p>
        <p class="text-[10px] text-gray-400 mt-0.5">Salary, Rent, Marketing</p>
    </div>
</div>

{{-- ============ ACCOUNT TYPE DISTRIBUTION ============ --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    {{-- Chart --}}
    <div class="lg:col-span-2 card !rounded-2xl">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-semibold text-gray-800">Debit / Credit Flow</h3>
                <p class="text-xs text-gray-500 mt-0.5">প্রতিদিন কত ডেবিট ও ক্রেডিট পোস্ট হলো</p>
            </div>
            <div class="flex items-center gap-3 text-xs">
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-blue-500"></span>Debit</span>
                <span class="flex items-center gap-1.5"><span class="w-3 h-3 rounded bg-rose-500"></span>Credit</span>
            </div>
        </div>
        <canvas id="dcChart" height="100"></canvas>
    </div>

    {{-- Account Distribution --}}
    <div class="card !rounded-2xl">
        <h3 class="font-semibold text-gray-800 mb-1">By Account Type</h3>
        <p class="text-xs text-gray-500 mb-4">কোন ধরনের অ্যাকাউন্টে কত পোস্ট</p>

        <div class="space-y-4">
            @php
            $types = [
                ['label'=>'Assets (Cash, Bank, A/R)', 'amount'=>'৮,৪৫,০০০', 'pct'=>34, 'color'=>'blue'],
                ['label'=>'Liabilities (A/P, VAT)', 'amount'=>'৪,১০,০০০', 'pct'=>17, 'color'=>'amber'],
                ['label'=>'Revenue', 'amount'=>'১২,৫০,০০০', 'pct'=>50, 'color'=>'emerald'],
                ['label'=>'Expenses', 'amount'=>'২,১০,০০০', 'pct'=>25, 'color'=>'rose'],
                ['label'=>'Equity', 'amount'=>'০', 'pct'=>0, 'color'=>'purple'],
            ];
            @endphp
            @foreach($types as $t)
            <div>
                <div class="flex items-center justify-between mb-1">
                    <span class="text-xs font-medium text-gray-700">{{ $t['label'] }}</span>
                    <span class="text-xs font-bold text-gray-800 num">৳ {{ $t['amount'] }}</span>
                </div>
                <div class="h-1.5 bg-gray-100 rounded-full overflow-hidden">
                    <div class="h-full rounded-full bg-gradient-to-r from-{{ $t['color'] }}-400 to-{{ $t['color'] }}-600" style="width: {{ $t['pct'] }}%"></div>
                </div>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ============ MAIN JOURNAL ENTRIES LIST ============ --}}
<div class="card !rounded-2xl">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
        <div>
            <h3 class="font-semibold text-gray-800">Journal Entry List</h3>
            <p class="text-xs text-gray-500 mt-0.5">তারিখ অনুযায়ী গ্রুপ করা — সম্প্রসারিত করে ডেবিট/ক্রেডিট লাইন দেখুন</p>
        </div>
        <div class="flex gap-2">
            <input type="text" placeholder="🔍 JE #, reference, account..." class="input max-w-xs text-sm">
            <button class="btn btn-outline text-xs">📤 Export</button>
            <button class="btn btn-primary text-xs">⬇️ PDF</button>
        </div>
    </div>

    {{-- ===== DATE GROUP: 31 JAN 2025 ===== --}}
    <div class="mb-4">
        <div class="sticky top-0 z-10 flex items-center justify-between py-2 px-3 bg-slate-50 rounded-lg mb-2 border border-slate-100">
            <div class="flex items-center gap-2">
                <span class="w-2 h-2 rounded-full bg-indigo-500"></span>
                <p class="text-sm font-bold text-slate-700">৩১ জানুয়ারি, ২০২৫</p>
                <span class="badge badge-info">৯ entries</span>
            </div>
            <div class="flex items-center gap-4 text-xs">
                <span class="text-slate-500">Debit:</span>
                <span class="font-bold text-blue-700 num">৳ ৩,৮৫,০০০</span>
                <span class="text-slate-500">Credit:</span>
                <span class="font-bold text-rose-700 num">৳ ৩,৮৫,০০০</span>
            </div>
        </div>

        {{-- ===== JE #1: POS SALE ===== --}}
        <details class="group mb-2 rounded-xl border border-gray-100 hover:border-indigo-100 hover:shadow-sm transition overflow-hidden">
            <summary class="cursor-pointer list-none flex items-center gap-4 p-4 relative">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-blue-500"></div>
                <div class="flex items-center gap-3 min-w-[140px]">
                    <div class="w-9 h-9 rounded-lg bg-blue-50 text-blue-600 flex items-center justify-center text-sm">🛒</div>
                    <div>
                        <p class="font-mono text-xs font-bold text-blue-700">JE-2025-0124</p>
                        <p class="text-[10px] text-gray-500">10:30 AM</p>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm">POS Sale — Invoice INV-1001</p>
                    <p class="text-xs text-gray-500 truncate">Wireless Mouse ×2, Cable ×1 · Customer: Rahim Ahmed · Counter #1</p>
                </div>
                <div class="hidden md:flex items-center gap-2">
                    <span class="badge badge-info">POS Sale</span>
                    <span class="badge badge-success">Posted</span>
                </div>
                <div class="text-right min-w-[110px]">
                    <p class="text-xs text-gray-500">Amount</p>
                    <p class="font-bold text-gray-800 num">৳ ৩,৪৫০</p>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </summary>

            {{-- Expanded Content --}}
            <div class="border-t border-gray-100 bg-gradient-to-b from-slate-50/60 to-white p-5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 text-xs">
                    <div>
                        <p class="text-gray-500 uppercase font-semibold tracking-wider text-[10px]">Entry Date</p>
                        <p class="font-medium text-gray-800 mt-1">31 Jan 2025 · 10:30:24 AM</p>
                    </div>
                    <div>
                        <p class="text-gray-500 uppercase font-semibold tracking-wider text-[10px]">Source Reference</p>
                        <p class="font-mono font-medium text-indigo-700 mt-1">transactions.id = 1</p>
                    </div>
                    <div>
                        <p class="text-gray-500 uppercase font-semibold tracking-wider text-[10px]">Posted By</p>
                        <p class="font-medium text-gray-800 mt-1">🤖 Auto (Observer: TransactionCreated)</p>
                    </div>
                </div>

                {{-- Debit / Credit lines --}}
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- DEBIT --}}
                    <div class="rounded-xl bg-blue-50/40 border border-blue-100 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[11px] font-bold text-blue-700 uppercase tracking-wider">Debit</p>
                            <p class="text-sm font-extrabold text-blue-700 num">৳ ৩,৪৫০</p>
                        </div>
                        <div class="flex items-start justify-between py-2 border-b border-dashed border-blue-200">
                            <div class="flex items-start gap-2">
                                <span class="font-mono text-[10px] font-bold text-blue-700 bg-blue-100 px-1.5 py-0.5 rounded">1010</span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Cash on Hand</p>
                                    <p class="text-[10px] text-gray-500">Current Asset · Counter #1</p>
                                </div>
                            </div>
                            <p class="font-bold num text-sm text-blue-700">৳ ৩,৪৫০</p>
                        </div>
                    </div>

                    {{-- CREDIT --}}
                    <div class="rounded-xl bg-rose-50/40 border border-rose-100 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[11px] font-bold text-rose-700 uppercase tracking-wider">Credit</p>
                            <p class="text-sm font-extrabold text-rose-700 num">৳ ৩,৪৫০</p>
                        </div>
                        <div class="flex items-start justify-between py-2 border-b border-dashed border-rose-200">
                            <div class="flex items-start gap-2">
                                <span class="font-mono text-[10px] font-bold text-rose-700 bg-rose-100 px-1.5 py-0.5 rounded">4010</span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Sales Revenue</p>
                                    <p class="text-[10px] text-gray-500">Operating Income · POS</p>
                                </div>
                            </div>
                            <p class="font-bold num text-sm text-rose-700">৳ ৩,০০০</p>
                        </div>
                        <div class="flex items-start justify-between py-2">
                            <div class="flex items-start gap-2">
                                <span class="font-mono text-[10px] font-bold text-rose-700 bg-rose-100 px-1.5 py-0.5 rounded">2210</span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">VAT Payable (15%)</p>
                                    <p class="text-[10px] text-gray-500">Current Liability</p>
                                </div>
                            </div>
                            <p class="font-bold num text-sm text-rose-700">৳ ৪৫০</p>
                        </div>
                    </div>
                </div>

                {{-- Footer --}}
                <div class="mt-4 pt-3 border-t border-gray-200 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2 text-emerald-700">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                            <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="font-semibold">Balanced</span>
                        <span class="text-gray-400">·</span>
                        <span class="text-gray-500">Dr = Cr = ৳ ৩,৪৫০</span>
                    </div>
                    <div class="flex gap-2">
                        <button class="text-indigo-600 hover:underline">View Source →</button>
                        <button class="text-indigo-600 hover:underline">Print Voucher</button>
                    </div>
                </div>
            </div>
        </details>

        {{-- ===== JE #2: COGS ENTRY ===== --}}
        <details class="group mb-2 rounded-xl border border-gray-100 hover:border-indigo-100 hover:shadow-sm transition overflow-hidden">
            <summary class="cursor-pointer list-none flex items-center gap-4 p-4 relative">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-amber-500"></div>
                <div class="flex items-center gap-3 min-w-[140px]">
                    <div class="w-9 h-9 rounded-lg bg-amber-50 text-amber-600 flex items-center justify-center text-sm">📦</div>
                    <div>
                        <p class="font-mono text-xs font-bold text-amber-700">JE-2025-0125</p>
                        <p class="text-[10px] text-gray-500">10:30 AM</p>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm">Cost of Goods Sold — INV-1001</p>
                    <p class="text-xs text-gray-500 truncate">Inventory reduction · Auto-posted from stock movement</p>
                </div>
                <div class="hidden md:flex items-center gap-2">
                    <span class="badge badge-warning">Inventory</span>
                    <span class="badge badge-success">Posted</span>
                </div>
                <div class="text-right min-w-[110px]">
                    <p class="text-xs text-gray-500">Amount</p>
                    <p class="font-bold text-gray-800 num">৳ ১,১০০</p>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </summary>

            <div class="border-t border-gray-100 bg-gradient-to-b from-slate-50/60 to-white p-5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 text-xs">
                    <div>
                        <p class="text-gray-500 uppercase font-semibold tracking-wider text-[10px]">Entry Date</p>
                        <p class="font-medium text-gray-800 mt-1">31 Jan 2025 · 10:30:24 AM</p>
                    </div>
                    <div>
                        <p class="text-gray-500 uppercase font-semibold tracking-wider text-[10px]">Source Reference</p>
                        <p class="font-mono font-medium text-indigo-700 mt-1">stock_movements.id = 102</p>
                    </div>
                    <div>
                        <p class="text-gray-500 uppercase font-semibold tracking-wider text-[10px]">Posted By</p>
                        <p class="font-medium text-gray-800 mt-1">🤖 Auto (StockMovedObserver)</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-xl bg-blue-50/40 border border-blue-100 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[11px] font-bold text-blue-700 uppercase tracking-wider">Debit</p>
                            <p class="text-sm font-extrabold text-blue-700 num">৳ ১,১০০</p>
                        </div>
                        <div class="flex items-start justify-between py-2">
                            <div class="flex items-start gap-2">
                                <span class="font-mono text-[10px] font-bold text-blue-700 bg-blue-100 px-1.5 py-0.5 rounded">5010</span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Cost of Goods Sold</p>
                                    <p class="text-[10px] text-gray-500">Direct Cost · FIFO method</p>
                                </div>
                            </div>
                            <p class="font-bold num text-sm text-blue-700">৳ ১,১০০</p>
                        </div>
                    </div>

                    <div class="rounded-xl bg-rose-50/40 border border-rose-100 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[11px] font-bold text-rose-700 uppercase tracking-wider">Credit</p>
                            <p class="text-sm font-extrabold text-rose-700 num">৳ ১,১০০</p>
                        </div>
                        <div class="flex items-start justify-between py-2">
                            <div class="flex items-start gap-2">
                                <span class="font-mono text-[10px] font-bold text-rose-700 bg-rose-100 px-1.5 py-0.5 rounded">1300</span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Inventory</p>
                                    <p class="text-[10px] text-gray-500">Current Asset · SKU-1024 ×5</p>
                                </div>
                            </div>
                            <p class="font-bold num text-sm text-rose-700">৳ ১,১০০</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-200 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2 text-emerald-700">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                            <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="font-semibold">Balanced</span>
                    </div>
                    <div class="flex gap-2">
                        <button class="text-indigo-600 hover:underline">View Source →</button>
                    </div>
                </div>
            </div>
        </details>

        {{-- ===== JE #3: ONLINE SALE (MULTI-VENDOR) ===== --}}
        <details class="group mb-2 rounded-xl border border-gray-100 hover:border-indigo-100 hover:shadow-sm transition overflow-hidden" open>
            <summary class="cursor-pointer list-none flex items-center gap-4 p-4 relative">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-purple-500"></div>
                <div class="flex items-center gap-3 min-w-[140px]">
                    <div class="w-9 h-9 rounded-lg bg-purple-50 text-purple-600 flex items-center justify-center text-sm">🏪</div>
                    <div>
                        <p class="font-mono text-xs font-bold text-purple-700">JE-2025-0126</p>
                        <p class="text-[10px] text-gray-500">02:15 PM</p>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm">Online Sale — INV-1002 (Multi-Vendor)</p>
                    <p class="text-xs text-gray-500 truncate">Bluetooth Speaker ×2 · Vendor: TechZone BD · bKash</p>
                </div>
                <div class="hidden md:flex items-center gap-2">
                    <span class="badge" style="background:#ede9fe;color:#6d28d9">Online Order</span>
                    <span class="badge badge-success">Posted</span>
                </div>
                <div class="text-right min-w-[110px]">
                    <p class="text-xs text-gray-500">Amount</p>
                    <p class="font-bold text-gray-800 num">৳ ৫,২০০</p>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </summary>

            <div class="border-t border-gray-100 bg-gradient-to-b from-slate-50/60 to-white p-5">
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4 text-xs">
                    <div>
                        <p class="text-gray-500 uppercase font-semibold tracking-wider text-[10px]">Entry Date</p>
                        <p class="font-medium text-gray-800 mt-1">31 Jan 2025 · 02:15:11 PM</p>
                    </div>
                    <div>
                        <p class="text-gray-500 uppercase font-semibold tracking-wider text-[10px]">Source Reference</p>
                        <p class="font-mono font-medium text-indigo-700 mt-1">transactions.id = 2</p>
                    </div>
                    <div>
                        <p class="text-gray-500 uppercase font-semibold tracking-wider text-[10px]">Posted By</p>
                        <p class="font-medium text-gray-800 mt-1">🤖 Auto · Vendor sale split logic</p>
                    </div>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    {{-- DEBIT --}}
                    <div class="rounded-xl bg-blue-50/40 border border-blue-100 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[11px] font-bold text-blue-700 uppercase tracking-wider">Debit</p>
                            <p class="text-sm font-extrabold text-blue-700 num">৳ ৫,২০০</p>
                        </div>
                        <div class="flex items-start justify-between py-2">
                            <div class="flex items-start gap-2">
                                <span class="font-mono text-[10px] font-bold text-blue-700 bg-blue-100 px-1.5 py-0.5 rounded">1050</span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">bKash Receivable</p>
                                    <p class="text-[10px] text-gray-500">Current Asset · Pending settlement</p>
                                </div>
                            </div>
                            <p class="font-bold num text-sm text-blue-700">৳ ৫,২০০</p>
                        </div>
                    </div>

                    {{-- CREDIT --}}
                    <div class="rounded-xl bg-rose-50/40 border border-rose-100 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[11px] font-bold text-rose-700 uppercase tracking-wider">Credit</p>
                            <p class="text-sm font-extrabold text-rose-700 num">৳ ৫,২০০</p>
                        </div>
                        <div class="flex items-start justify-between py-2 border-b border-dashed border-rose-200">
                            <div class="flex items-start gap-2">
                                <span class="font-mono text-[10px] font-bold text-rose-700 bg-rose-100 px-1.5 py-0.5 rounded">2020</span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Vendor Payable — TechZone BD</p>
                                    <p class="text-[10px] text-gray-500">Current Liability · Net of 15% commission</p>
                                </div>
                            </div>
                            <p class="font-bold num text-sm text-rose-700">৳ ৪,৪২০</p>
                        </div>
                        <div class="flex items-start justify-between py-2">
                            <div class="flex items-start gap-2">
                                <span class="font-mono text-[10px] font-bold text-rose-700 bg-rose-100 px-1.5 py-0.5 rounded">4030</span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Commission Revenue</p>
                                    <p class="text-[10px] text-gray-500">Platform Income · 15%</p>
                                </div>
                            </div>
                            <p class="font-bold num text-sm text-rose-700">৳ ৭৮০</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-200 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2 text-emerald-700">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                            <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="font-semibold">Balanced</span>
                        <span class="text-gray-400">·</span>
                        <span class="text-gray-500">Vendor ৳৪,৪২০ + Platform ৳৭৮০ = ৳৫,২০০</span>
                    </div>
                    <button class="text-indigo-600 hover:underline">View Vendor Ledger →</button>
                </div>
            </div>
        </details>

        {{-- ===== JE #4: CUSTOMER PAYMENT ===== --}}
        <details class="group mb-2 rounded-xl border border-gray-100 hover:border-indigo-100 hover:shadow-sm transition overflow-hidden">
            <summary class="cursor-pointer list-none flex items-center gap-4 p-4 relative">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-emerald-500"></div>
                <div class="flex items-center gap-3 min-w-[140px]">
                    <div class="w-9 h-9 rounded-lg bg-emerald-50 text-emerald-600 flex items-center justify-center text-sm">💰</div>
                    <div>
                        <p class="font-mono text-xs font-bold text-emerald-700">JE-2025-0127</p>
                        <p class="text-[10px] text-gray-500">03:20 PM</p>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm">Customer Due Payment — Sadia Khan</p>
                    <p class="text-xs text-gray-500 truncate">Partial payment ৳ ৫,০০০ via bKash · Against INV-1002</p>
                </div>
                <div class="hidden md:flex items-center gap-2">
                    <span class="badge badge-success">Customer Payment</span>
                    <span class="badge badge-success">Posted</span>
                </div>
                <div class="text-right min-w-[110px]">
                    <p class="text-xs text-gray-500">Amount</p>
                    <p class="font-bold text-gray-800 num">৳ ৫,০০০</p>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </summary>

            <div class="border-t border-gray-100 bg-gradient-to-b from-slate-50/60 to-white p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-xl bg-blue-50/40 border border-blue-100 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[11px] font-bold text-blue-700 uppercase tracking-wider">Debit</p>
                            <p class="text-sm font-extrabold text-blue-700 num">৳ ৫,০০০</p>
                        </div>
                        <div class="flex items-start justify-between py-2">
                            <div class="flex items-start gap-2">
                                <span class="font-mono text-[10px] font-bold text-blue-700 bg-blue-100 px-1.5 py-0.5 rounded">1020</span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">bKash Merchant Account</p>
                                    <p class="text-[10px] text-gray-500">Current Asset · Txn BK8821009</p>
                                </div>
                            </div>
                            <p class="font-bold num text-sm text-blue-700">৳ ৫,০০০</p>
                        </div>
                    </div>

                    <div class="rounded-xl bg-rose-50/40 border border-rose-100 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[11px] font-bold text-rose-700 uppercase tracking-wider">Credit</p>
                            <p class="text-sm font-extrabold text-rose-700 num">৳ ৫,০০০</p>
                        </div>
                        <div class="flex items-start justify-between py-2">
                            <div class="flex items-start gap-2">
                                <span class="font-mono text-[10px] font-bold text-rose-700 bg-rose-100 px-1.5 py-0.5 rounded">1100</span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Accounts Receivable — Sadia</p>
                                    <p class="text-[10px] text-gray-500">Current Asset · Due reduced</p>
                                </div>
                            </div>
                            <p class="font-bold num text-sm text-rose-700">৳ ৫,০০০</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-200 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2 text-emerald-700">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                            <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="font-semibold">Balanced</span>
                    </div>
                    <button class="text-indigo-600 hover:underline">View Customer Ledger →</button>
                </div>
            </div>
        </details>

        {{-- ===== JE #5: VENDOR PAYOUT ===== --}}
        <details class="group mb-2 rounded-xl border border-gray-100 hover:border-indigo-100 hover:shadow-sm transition overflow-hidden">
            <summary class="cursor-pointer list-none flex items-center gap-4 p-4 relative">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-slate-500"></div>
                <div class="flex items-center gap-3 min-w-[140px]">
                    <div class="w-9 h-9 rounded-lg bg-slate-100 text-slate-600 flex items-center justify-center text-sm">🏦</div>
                    <div>
                        <p class="font-mono text-xs font-bold text-slate-700">JE-2025-0128</p>
                        <p class="text-[10px] text-gray-500">04:45 PM</p>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm">Vendor Payout — TechZone BD</p>
                    <p class="text-xs text-gray-500 truncate">Jan 2025 settlement · Bank Transfer · PO-2025-001</p>
                </div>
                <div class="hidden md:flex items-center gap-2">
                    <span class="badge" style="background:#e0e7ff;color:#4338ca">Vendor Payout</span>
                    <span class="badge badge-success">Posted</span>
                </div>
                <div class="text-right min-w-[110px]">
                    <p class="text-xs text-gray-500">Amount</p>
                    <p class="font-bold text-gray-800 num">৳ ৪,৪২০</p>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </summary>

            <div class="border-t border-gray-100 bg-gradient-to-b from-slate-50/60 to-white p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-xl bg-blue-50/40 border border-blue-100 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[11px] font-bold text-blue-700 uppercase tracking-wider">Debit</p>
                            <p class="text-sm font-extrabold text-blue-700 num">৳ ৪,৪২০</p>
                        </div>
                        <div class="flex items-start justify-between py-2">
                            <div class="flex items-start gap-2">
                                <span class="font-mono text-[10px] font-bold text-blue-700 bg-blue-100 px-1.5 py-0.5 rounded">2020</span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Vendor Payable — TechZone BD</p>
                                    <p class="text-[10px] text-gray-500">Liability cleared</p>
                                </div>
                            </div>
                            <p class="font-bold num text-sm text-blue-700">৳ ৪,৪২০</p>
                        </div>
                    </div>

                    <div class="rounded-xl bg-rose-50/40 border border-rose-100 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[11px] font-bold text-rose-700 uppercase tracking-wider">Credit</p>
                            <p class="text-sm font-extrabold text-rose-700 num">৳ ৪,৪২০</p>
                        </div>
                        <div class="flex items-start justify-between py-2">
                            <div class="flex items-start gap-2">
                                <span class="font-mono text-[10px] font-bold text-rose-700 bg-rose-100 px-1.5 py-0.5 rounded">1021</span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">City Bank — 1234</p>
                                    <p class="text-[10px] text-gray-500">Current Asset · Bank Transfer</p>
                                </div>
                            </div>
                            <p class="font-bold num text-sm text-rose-700">৳ ৪,৪২০</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-200 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2 text-emerald-700">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                            <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="font-semibold">Balanced</span>
                    </div>
                    <button class="text-indigo-600 hover:underline">View Payout →</button>
                </div>
            </div>
        </details>

        {{-- ===== JE #6: EXPENSE ===== --}}
        <details class="group mb-2 rounded-xl border border-gray-100 hover:border-indigo-100 hover:shadow-sm transition overflow-hidden">
            <summary class="cursor-pointer list-none flex items-center gap-4 p-4 relative">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-rose-500"></div>
                <div class="flex items-center gap-3 min-w-[140px]">
                    <div class="w-9 h-9 rounded-lg bg-rose-50 text-rose-600 flex items-center justify-center text-sm">📝</div>
                    <div>
                        <p class="font-mono text-xs font-bold text-rose-700">JE-2025-0129</p>
                        <p class="text-[10px] text-gray-500">05:00 PM</p>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm">Salary Expense — January 2025</p>
                    <p class="text-xs text-gray-500 truncate">12 employees · Bank transfer</p>
                </div>
                <div class="hidden md:flex items-center gap-2">
                    <span class="badge badge-danger">Expense</span>
                    <span class="badge badge-success">Posted</span>
                </div>
                <div class="text-right min-w-[110px]">
                    <p class="text-xs text-gray-500">Amount</p>
                    <p class="font-bold text-gray-800 num">৳ ১,২০,০০০</p>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </summary>

            <div class="border-t border-gray-100 bg-gradient-to-b from-slate-50/60 to-white p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-xl bg-blue-50/40 border border-blue-100 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[11px] font-bold text-blue-700 uppercase tracking-wider">Debit</p>
                            <p class="text-sm font-extrabold text-blue-700 num">৳ ১,২০,০০০</p>
                        </div>
                        <div class="flex items-start justify-between py-2">
                            <div class="flex items-start gap-2">
                                <span class="font-mono text-[10px] font-bold text-blue-700 bg-blue-100 px-1.5 py-0.5 rounded">6010</span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Salary Expense</p>
                                    <p class="text-[10px] text-gray-500">Operating Expense</p>
                                </div>
                            </div>
                            <p class="font-bold num text-sm text-blue-700">৳ ১,২০,০০০</p>
                        </div>
                    </div>

                    <div class="rounded-xl bg-rose-50/40 border border-rose-100 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[11px] font-bold text-rose-700 uppercase tracking-wider">Credit</p>
                            <p class="text-sm font-extrabold text-rose-700 num">৳ ১,২০,০০০</p>
                        </div>
                        <div class="flex items-start justify-between py-2">
                            <div class="flex items-start gap-2">
                                <span class="font-mono text-[10px] font-bold text-rose-700 bg-rose-100 px-1.5 py-0.5 rounded">1021</span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">City Bank — 1234</p>
                                    <p class="text-[10px] text-gray-500">Current Asset</p>
                                </div>
                            </div>
                            <p class="font-bold num text-sm text-rose-700">৳ ১,২০,০০০</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-200 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2 text-emerald-700">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                            <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="font-semibold">Balanced</span>
                    </div>
                    <button class="text-indigo-600 hover:underline">View Expense →</button>
                </div>
            </div>
        </details>

        {{-- ===== JE #7: RETURN ===== --}}
        <details class="group mb-2 rounded-xl border border-gray-100 hover:border-indigo-100 hover:shadow-sm transition overflow-hidden">
            <summary class="cursor-pointer list-none flex items-center gap-4 p-4 relative">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-orange-500"></div>
                <div class="flex items-center gap-3 min-w-[140px]">
                    <div class="w-9 h-9 rounded-lg bg-orange-50 text-orange-600 flex items-center justify-center text-sm">↩️</div>
                    <div>
                        <p class="font-mono text-xs font-bold text-orange-700">JE-2025-0130</p>
                        <p class="text-[10px] text-gray-500">06:15 PM</p>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm">Sales Return — RET-0124</p>
                    <p class="text-xs text-gray-500 truncate">Damaged Bluetooth Speaker returned · Cash refund</p>
                </div>
                <div class="hidden md:flex items-center gap-2">
                    <span class="badge badge-danger">Return</span>
                    <span class="badge badge-success">Posted</span>
                </div>
                <div class="text-right min-w-[110px]">
                    <p class="text-xs text-gray-500">Amount</p>
                    <p class="font-bold text-gray-800 num">৳ ২,৬০০</p>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </summary>

            <div class="border-t border-gray-100 bg-gradient-to-b from-slate-50/60 to-white p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-xl bg-blue-50/40 border border-blue-100 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[11px] font-bold text-blue-700 uppercase tracking-wider">Debit</p>
                            <p class="text-sm font-extrabold text-blue-700 num">৳ ২,৬০০</p>
                        </div>
                        <div class="flex items-start justify-between py-2 border-b border-dashed border-blue-200">
                            <div class="flex items-start gap-2">
                                <span class="font-mono text-[10px] font-bold text-blue-700 bg-blue-100 px-1.5 py-0.5 rounded">4020</span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Sales Return (Contra-Revenue)</p>
                                    <p class="text-[10px] text-gray-500">Reduces gross revenue</p>
                                </div>
                            </div>
                            <p class="font-bold num text-sm text-blue-700">৳ ২,২৬১</p>
                        </div>
                        <div class="flex items-start justify-between py-2">
                            <div class="flex items-start gap-2">
                                <span class="font-mono text-[10px] font-bold text-blue-700 bg-blue-100 px-1.5 py-0.5 rounded">2210</span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">VAT Payable (reversal)</p>
                                    <p class="text-[10px] text-gray-500">Current Liability</p>
                                </div>
                            </div>
                            <p class="font-bold num text-sm text-blue-700">৳ ৩৩৯</p>
                        </div>
                    </div>

                    <div class="rounded-xl bg-rose-50/40 border border-rose-100 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[11px] font-bold text-rose-700 uppercase tracking-wider">Credit</p>
                            <p class="text-sm font-extrabold text-rose-700 num">৳ ২,৬০০</p>
                        </div>
                        <div class="flex items-start justify-between py-2">
                            <div class="flex items-start gap-2">
                                <span class="font-mono text-[10px] font-bold text-rose-700 bg-rose-100 px-1.5 py-0.5 rounded">1010</span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Cash on Hand</p>
                                    <p class="text-[10px] text-gray-500">Refund paid</p>
                                </div>
                            </div>
                            <p class="font-bold num text-sm text-rose-700">৳ ২,৬০০</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-200 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2 text-emerald-700">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                            <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="font-semibold">Balanced</span>
                    </div>
                    <button class="text-indigo-600 hover:underline">View Return →</button>
                </div>
            </div>
        </details>

        {{-- ===== JE #8: PURCHASE ===== --}}
        <details class="group mb-2 rounded-xl border border-gray-100 hover:border-indigo-100 hover:shadow-sm transition overflow-hidden">
            <summary class="cursor-pointer list-none flex items-center gap-4 p-4 relative">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-teal-500"></div>
                <div class="flex items-center gap-3 min-w-[140px]">
                    <div class="w-9 h-9 rounded-lg bg-teal-50 text-teal-600 flex items-center justify-center text-sm">🚚</div>
                    <div>
                        <p class="font-mono text-xs font-bold text-teal-700">JE-2025-0131</p>
                        <p class="text-[10px] text-gray-500">07:30 PM</p>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm">Inventory Purchase — PO-2025-0145</p>
                    <p class="text-xs text-gray-500 truncate">TechZone BD · 12 items received · Credit purchase</p>
                </div>
                <div class="hidden md:flex items-center gap-2">
                    <span class="badge" style="background:#ccfbf1;color:#0f766e">Purchase</span>
                    <span class="badge badge-success">Posted</span>
                </div>
                <div class="text-right min-w-[110px]">
                    <p class="text-xs text-gray-500">Amount</p>
                    <p class="font-bold text-gray-800 num">৳ ২৮,৭৫০</p>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </summary>

            <div class="border-t border-gray-100 bg-gradient-to-b from-slate-50/60 to-white p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-xl bg-blue-50/40 border border-blue-100 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[11px] font-bold text-blue-700 uppercase tracking-wider">Debit</p>
                            <p class="text-sm font-extrabold text-blue-700 num">৳ ২৮,৭৫০</p>
                        </div>
                        <div class="flex items-start justify-between py-2 border-b border-dashed border-blue-200">
                            <div class="flex items-start gap-2">
                                <span class="font-mono text-[10px] font-bold text-blue-700 bg-blue-100 px-1.5 py-0.5 rounded">1300</span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Inventory</p>
                                    <p class="text-[10px] text-gray-500">Current Asset · 12 items</p>
                                </div>
                            </div>
                            <p class="font-bold num text-sm text-blue-700">৳ ২৫,০০০</p>
                        </div>
                        <div class="flex items-start justify-between py-2">
                            <div class="flex items-start gap-2">
                                <span class="font-mono text-[10px] font-bold text-blue-700 bg-blue-100 px-1.5 py-0.5 rounded">1410</span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Input VAT Receivable</p>
                                    <p class="text-[10px] text-gray-500">Current Asset · 15%</p>
                                </div>
                            </div>
                            <p class="font-bold num text-sm text-blue-700">৳ ৩,৭৫০</p>
                        </div>
                    </div>

                    <div class="rounded-xl bg-rose-50/40 border border-rose-100 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[11px] font-bold text-rose-700 uppercase tracking-wider">Credit</p>
                            <p class="text-sm font-extrabold text-rose-700 num">৳ ২৮,৭৫০</p>
                        </div>
                        <div class="flex items-start justify-between py-2">
                            <div class="flex items-start gap-2">
                                <span class="font-mono text-[10px] font-bold text-rose-700 bg-rose-100 px-1.5 py-0.5 rounded">2010</span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Accounts Payable — TechZone</p>
                                    <p class="text-[10px] text-gray-500">Current Liability · PO-2025-0145</p>
                                </div>
                            </div>
                            <p class="font-bold num text-sm text-rose-700">৳ ২৮,৭৫০</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-200 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2 text-emerald-700">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                            <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="font-semibold">Balanced</span>
                    </div>
                    <button class="text-indigo-600 hover:underline">View PO →</button>
                </div>
            </div>
        </details>

        {{-- ===== JE #9: MANUAL ADJUSTMENT ===== --}}
        <details class="group mb-2 rounded-xl border border-amber-200 bg-amber-50/30 hover:shadow-sm transition overflow-hidden">
            <summary class="cursor-pointer list-none flex items-center gap-4 p-4 relative">
                <div class="absolute left-0 top-0 bottom-0 w-1 bg-amber-500"></div>
                <div class="flex items-center gap-3 min-w-[140px]">
                    <div class="w-9 h-9 rounded-lg bg-amber-100 text-amber-700 flex items-center justify-center text-sm">✏️</div>
                    <div>
                        <p class="font-mono text-xs font-bold text-amber-700">JE-2025-0132</p>
                        <p class="text-[10px] text-gray-500">09:00 PM</p>
                    </div>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 text-sm">Manual Adjustment — Goodwill Discount</p>
                    <p class="text-xs text-gray-500 truncate">Customer: Sadia Khan · Approved by Manager</p>
                </div>
                <div class="hidden md:flex items-center gap-2">
                    <span class="badge" style="background:#fef3c7;color:#b45309">Manual Entry</span>
                    <span class="badge badge-success">Posted</span>
                </div>
                <div class="text-right min-w-[110px]">
                    <p class="text-xs text-gray-500">Amount</p>
                    <p class="font-bold text-gray-800 num">৳ ২৫০</p>
                </div>
                <svg class="w-4 h-4 text-gray-400 group-open:rotate-180 transition-transform" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"/>
                </svg>
            </summary>

            <div class="border-t border-gray-100 bg-gradient-to-b from-slate-50/60 to-white p-5">
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="rounded-xl bg-blue-50/40 border border-blue-100 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[11px] font-bold text-blue-700 uppercase tracking-wider">Debit</p>
                            <p class="text-sm font-extrabold text-blue-700 num">৳ ২৫০</p>
                        </div>
                        <div class="flex items-start justify-between py-2">
                            <div class="flex items-start gap-2">
                                <span class="font-mono text-[10px] font-bold text-blue-700 bg-blue-100 px-1.5 py-0.5 rounded">4050</span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Discount Allowed</p>
                                    <p class="text-[10px] text-gray-500">Contra-Revenue · Goodwill</p>
                                </div>
                            </div>
                            <p class="font-bold num text-sm text-blue-700">৳ ২৫০</p>
                        </div>
                    </div>

                    <div class="rounded-xl bg-rose-50/40 border border-rose-100 p-4">
                        <div class="flex items-center justify-between mb-3">
                            <p class="text-[11px] font-bold text-rose-700 uppercase tracking-wider">Credit</p>
                            <p class="text-sm font-extrabold text-rose-700 num">৳ ২৫০</p>
                        </div>
                        <div class="flex items-start justify-between py-2">
                            <div class="flex items-start gap-2">
                                <span class="font-mono text-[10px] font-bold text-rose-700 bg-rose-100 px-1.5 py-0.5 rounded">1100</span>
                                <div>
                                    <p class="text-sm font-medium text-gray-800">Accounts Receivable — Sadia</p>
                                    <p class="text-[10px] text-gray-500">Due reduced</p>
                                </div>
                            </div>
                            <p class="font-bold num text-sm text-rose-700">৳ ২৫০</p>
                        </div>
                    </div>
                </div>

                <div class="mt-4 pt-3 border-t border-gray-200 flex items-center justify-between text-xs">
                    <div class="flex items-center gap-2 text-emerald-700">
                        <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="3">
                            <path d="M5 13l4 4L19 7" stroke-linecap="round" stroke-linejoin="round"/>
                        </svg>
                        <span class="font-semibold">Balanced</span>
                        <span class="text-gray-400">·</span>
                        <span class="text-amber-700">✍️ Manual — audit required</span>
                    </div>
                    <button class="text-indigo-600 hover:underline">View Audit →</button>
                </div>
            </div>
        </details>
    </div>

    {{-- ===== SUMMARY FOOTER ===== --}}
    <div class="mt-6 rounded-xl bg-gradient-to-br from-slate-50 to-white border border-slate-200 p-5">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
            <div>
                <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Total Journal Entries</p>
                <p class="text-2xl font-extrabold text-gray-800 num mt-1">৯</p>
                <p class="text-xs text-gray-500 mt-1">সব balanced ✓</p>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Debit Side Total</p>
                <p class="text-2xl font-extrabold text-blue-700 num mt-1">৳ ৫,৪৭,৩৯০</p>
                <p class="text-xs text-gray-500 mt-1">All modules combined</p>
            </div>
            <div>
                <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Credit Side Total</p>
                <p class="text-2xl font-extrabold text-rose-700 num mt-1">৳ ৫,৪৭,৩৯০</p>
                <p class="text-xs text-emerald-600 mt-1 font-semibold">Variance: ৳ ০ ✓</p>
            </div>
        </div>
    </div>
</div>

@push('styles')
<style>
    .num { font-variant-numeric: tabular-nums; letter-spacing: -0.02em; }
    details summary::-webkit-details-marker { display: none; }
    details[open] .group-open\:rotate-180 { transform: rotate(180deg); }
</style>
@endpush

@push('scripts')
<script>
new Chart(document.getElementById('dcChart'), {
    type: 'bar',
    data: {
        labels: ['Jan 25','Jan 26','Jan 27','Jan 28','Jan 29','Jan 30','Jan 31'],
        datasets: [
            { label: 'Debit', data: [42000, 38000, 55000, 48000, 62000, 71000, 385000],
              backgroundColor: 'rgba(59,130,246,0.85)', borderRadius: 6, barThickness: 20 },
            { label: 'Credit', data: [42000, 38000, 55000, 48000, 62000, 71000, 385000],
              backgroundColor: 'rgba(244,63,94,0.85)', borderRadius: 6, barThickness: 20 }
        ]
    },
    options: {
        responsive: true,
        plugins: {
            legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } },
            tooltip: { callbacks: { label: (c) => c.dataset.label + ': ৳' + c.parsed.y.toLocaleString() } }
        },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { callback: v => '৳' + (v/1000) + 'k', font: { size: 10 } } },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});
</script>
@endpush

@endsection