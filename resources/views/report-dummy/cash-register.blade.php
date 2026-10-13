@extends('report-dummy.report')
@section('title', 'Cash Register / Day Close')
@section('page-title', 'Cash Register — Day Close')
@section('page-subtitle', 'প্রতিদিনের ক্যাশ মিলানো ও হস্তান্তর')

@section('content')

{{-- ===== HEADER FILTER ===== --}}
<div class="card !rounded-2xl mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
        <div>
            <label class="label">Business Date</label>
            <input type="date" class="input" value="2025-01-31">
        </div>
        <div>
            <label class="label">Counter / Outlet</label>
            <select class="input">
                <option>Counter #1 — Main Store</option>
                <option>Counter #2 — Main Store</option>
                <option>Counter #3 — Branch</option>
            </select>
        </div>
        <div>
            <label class="label">Shift</label>
            <select class="input">
                <option>Full Day</option>
                <option>Morning (9AM–3PM)</option>
                <option>Evening (3PM–9PM)</option>
            </select>
        </div>
        <div class="flex gap-2">
            <button class="btn btn-outline flex-1 justify-center">🔄 Refresh</button>
            <button class="btn btn-primary flex-1 justify-center">🔒 Close Day</button>
        </div>
    </div>
</div>

{{-- ===== HERO: DAY CLOSE SUMMARY ===== --}}
<div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-slate-900 via-slate-800 to-indigo-900 text-white p-6 mb-6 shadow-xl">
    <div class="absolute -right-16 -top-16 w-64 h-64 bg-indigo-500/20 rounded-full blur-3xl"></div>
    <div class="absolute -left-16 -bottom-16 w-64 h-64 bg-purple-500/20 rounded-full blur-3xl"></div>

    <div class="relative">
        <div class="flex flex-col lg:flex-row justify-between gap-6">
            {{-- Left: Date & Shift Info --}}
            <div>
                <div class="flex items-center gap-3 mb-3">
                    <span class="inline-flex items-center gap-1.5 px-3 py-1 rounded-full bg-emerald-500/20 text-emerald-300 text-xs font-semibold">
                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-400 pulse-dot"></span>
                        Day Open
                    </span>
                    <span class="text-slate-400 text-xs">Opened at 09:00 AM by Rahim</span>
                </div>
                <h2 class="text-2xl font-bold">Counter #1 — Main Store</h2>
                <p class="text-slate-300 text-sm mt-1">৩১ জানুয়ারি, ২০২৫ · শুক্রবার · Full Day Shift</p>

                <div class="grid grid-cols-3 gap-6 mt-6">
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Opening Cash</p>
                        <p class="text-xl font-bold num mt-1">৳ ২০,০০০</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Total Sales</p>
                        <p class="text-xl font-bold num mt-1 text-emerald-400">৳ ১,২৫,৪০০</p>
                    </div>
                    <div>
                        <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Transactions</p>
                        <p class="text-xl font-bold num mt-1">৩৪২</p>
                    </div>
                </div>
            </div>

            {{-- Right: Expected Closing --}}
            <div class="lg:min-w-[280px] rounded-xl bg-white/5 backdrop-blur-sm border border-white/10 p-5">
                <p class="text-[10px] uppercase tracking-wider text-slate-400 font-semibold">Expected Closing Cash</p>
                <p class="text-4xl font-extrabold num mt-2 tracking-tight">৳ ৬৮,৭৫০</p>
                <p class="text-xs text-slate-400 mt-2">Physical count: <span class="text-amber-300 font-semibold">Pending</span></p>
                <button class="w-full mt-4 py-2.5 rounded-lg bg-white text-slate-900 font-semibold text-sm hover:bg-slate-100 transition">
                    🧮 Count Cash Now
                </button>
            </div>
        </div>
    </div>
</div>

{{-- ===== QUICK STATS GRID ===== --}}
<div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-6">
    <div class="rounded-2xl bg-white ring-1 ring-gray-900/5 p-4 shadow-[0_1px_2px_rgba(16,24,40,0.04),0_8px_24px_-12px_rgba(16,24,40,0.08)]">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-7 h-7 rounded-lg bg-emerald-100 text-emerald-600 flex items-center justify-center text-sm">💵</div>
            <p class="text-[11px] font-semibold text-gray-500 uppercase">Cash Sales</p>
        </div>
        <p class="text-2xl font-extrabold text-gray-800 num mt-1">৳ ৫২,০০০</p>
        <p class="text-[10px] text-gray-400 mt-0.5">১৮২ transactions</p>
    </div>
    <div class="rounded-2xl bg-white ring-1 ring-gray-900/5 p-4 shadow-[0_1px_2px_rgba(16,24,40,0.04),0_8px_24px_-12px_rgba(16,24,40,0.08)]">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-7 h-7 rounded-lg bg-blue-100 text-blue-600 flex items-center justify-center text-sm">💳</div>
            <p class="text-[11px] font-semibold text-gray-500 uppercase">Card Sales</p>
        </div>
        <p class="text-2xl font-extrabold text-gray-800 num mt-1">৳ ৩৮,৪০০</p>
        <p class="text-[10px] text-gray-400 mt-0.5">৮৪ transactions</p>
    </div>
    <div class="rounded-2xl bg-white ring-1 ring-gray-900/5 p-4 shadow-[0_1px_2px_rgba(16,24,40,0.04),0_8px_24px_-12px_rgba(16,24,40,0.08)]">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-7 h-7 rounded-lg bg-pink-100 text-pink-600 flex items-center justify-center text-sm">📱</div>
            <p class="text-[11px] font-semibold text-gray-500 uppercase">bKash</p>
        </div>
        <p class="text-2xl font-extrabold text-gray-800 num mt-1">৳ ২৫,০০০</p>
        <p class="text-[10px] text-gray-400 mt-0.5">৫৬ transactions</p>
    </div>
    <div class="rounded-2xl bg-white ring-1 ring-gray-900/5 p-4 shadow-[0_1px_2px_rgba(16,24,40,0.04),0_8px_24px_-12px_rgba(16,24,40,0.08)]">
        <div class="flex items-center gap-2 mb-1">
            <div class="w-7 h-7 rounded-lg bg-purple-100 text-purple-600 flex items-center justify-center text-sm">🏦</div>
            <p class="text-[11px] font-semibold text-gray-500 uppercase">Bank Transfer</p>
        </div>
        <p class="text-2xl font-extrabold text-gray-800 num mt-1">৳ ১০,০০০</p>
        <p class="text-[10px] text-gray-400 mt-0.5">২০ transactions</p>
    </div>
</div>

{{-- ===== CASH RECONCILIATION ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- Left: Cash Flow Ledger --}}
    <div class="lg:col-span-2 card !rounded-2xl">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-semibold text-gray-800">Cash Movement</h3>
                <p class="text-xs text-gray-500 mt-0.5">দিনের শুরু থেকে এখন পর্যন্ত</p>
            </div>
            <button class="btn btn-outline text-xs">+ Add Cash Drop</button>
        </div>

        <div class="space-y-1">
            @php
            $movements = [
                ['label'=>'Opening Cash', 'time'=>'09:00 AM', 'amount'=>'২০,০০০', 'type'=>'neutral', 'icon'=>'🔓'],
                ['label'=>'Cash Sales (182 txns)', 'time'=>'09:15 AM – 08:45 PM', 'amount'=>'+ ৫২,০০০', 'type'=>'in', 'icon'=>'💵'],
                ['label'=>'Cash Refunds (4)', 'time'=>'11:20 AM – 04:30 PM', 'amount'=>'− ২,৮৫০', 'type'=>'out', 'icon'=>'↩️'],
                ['label'=>'Cash Drop → Safe', 'time'=>'02:00 PM', 'amount'=>'− ১৫,০০০', 'type'=>'out', 'icon'=>'🏦'],
                ['label'=>'Vendor Payout (Cash)', 'time'=>'03:45 PM', 'amount'=>'− ৫,০০০', 'type'=>'out', 'icon'=>'💸'],
                ['label'=>'Petty Expense', 'time'=>'04:15 PM', 'amount'=>'− ৪০০', 'type'=>'out', 'icon'=>'📝'],
                ['label'=>'Card → Bank (auto-settled)', 'time'=>'08:45 PM', 'amount'=>'০', 'type'=>'neutral', 'icon'=>'💳'],
            ];
            @endphp
            @foreach($movements as $m)
            <div class="flex items-center gap-3 py-3 px-3 rounded-lg hover:bg-gray-50/80 transition group">
                <div class="w-9 h-9 rounded-lg bg-gray-100 flex items-center justify-center text-sm">{{ $m['icon'] }}</div>
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-800">{{ $m['label'] }}</p>
                    <p class="text-xs text-gray-400">{{ $m['time'] }}</p>
                </div>
                <p class="font-bold num text-base
                    {{ $m['type'] == 'in' ? 'text-emerald-600' : ($m['type'] == 'out' ? 'text-rose-600' : 'text-gray-700') }}">
                    ৳ {{ $m['amount'] }}
                </p>
            </div>
            @endforeach
        </div>

        <div class="mt-4 pt-4 border-t-2 border-dashed border-gray-200 flex items-center justify-between">
            <p class="font-bold text-gray-700 text-sm">Expected Cash in Drawer</p>
            <p class="text-2xl font-extrabold num grad-text">৳ ৬৮,৭৫০</p>
        </div>
    </div>

    {{-- Right: Denomination Counter --}}
    <div class="card !rounded-2xl">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-semibold text-gray-800">Cash Count</h3>
                <p class="text-xs text-gray-500 mt-0.5">নোট গুনে লিখুন</p>
            </div>
            <span class="badge badge-warning">In Progress</span>
        </div>

        <div class="space-y-2">
            @php
            $denoms = [
                ['val'=>1000, 'count'=>42, 'total'=>'৪২,০০০'],
                ['val'=>500, 'count'=>28, 'total'=>'১৪,০০০'],
                ['val'=>200, 'count'=>35, 'total'=>'৭,০০০'],
                ['val'=>100, 'count'=>48, 'total'=>'৪,৮০০'],
                ['val'=>50, 'count'=>20, 'total'=>'১,০০০'],
                ['val'=>20, 'count'=>15, 'total'=>'৩০০'],
            ];
            @endphp
            @foreach($denoms as $d)
            <div class="flex items-center gap-3 py-1.5">
                <div class="w-14 text-xs font-semibold text-gray-600 num">৳{{ $d['val'] }}</div>
                <div class="text-gray-400 text-xs">×</div>
                <input type="number" value="{{ $d['count'] }}" class="w-16 px-2 py-1 border border-gray-200 rounded-md text-xs text-center num focus:ring-2 focus:ring-indigo-500 outline-none">
                <div class="flex-1 text-right text-sm font-semibold text-gray-700 num">৳ {{ $d['total'] }}</div>
            </div>
            @endforeach
        </div>

        <div class="mt-4 pt-4 border-t border-gray-100">
            <div class="flex justify-between text-sm mb-2">
                <span class="text-gray-600">Total Counted</span>
                <span class="font-extrabold text-gray-800 num">৳ ৬৯,১০০</span>
            </div>
            <div class="flex justify-between text-sm mb-2">
                <span class="text-gray-600">Expected</span>
                <span class="font-bold text-gray-700 num">৳ ৬৮,৭५০</span>
            </div>
            <div class="flex justify-between text-sm pt-2 border-t border-dashed">
                <span class="font-bold text-emerald-700">Variance</span>
                <span class="font-extrabold text-emerald-600 num">+ ৳ ৩৫০</span>
            </div>
            <p class="text-[10px] text-emerald-600 mt-1">অতিরিক্ত ৩৫০ টাকা — সম্ভবত ছোট নোটে রাউন্ডিং</p>
        </div>

        <button class="w-full mt-4 py-2.5 rounded-lg bg-emerald-600 text-white font-semibold text-sm hover:bg-emerald-700 transition">
            ✓ Confirm Cash Count
        </button>
    </div>
</div>

{{-- ===== CASHIER-WISE BREAKDOWN ===== --}}
<div class="card !rounded-2xl mb-6">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h3 class="font-semibold text-gray-800">Cashier-wise Summary</h3>
            <p class="text-xs text-gray-500 mt-0.5">প্রতিটা ক্যাশিয়ারের বিক্রয় ও হস্তান্তর</p>
        </div>
        <button class="btn btn-outline text-xs">📊 Detailed View</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @php
        $cashiers = [
            ['name'=>'Rahim', 'init'=>'রা', 'txns'=>182, 'sales'=>'৫৮,৪০০', 'cash'=>'৩২,৫০০', 'diff'=>'0', 'status'=>'ok'],
            ['name'=>'Karim', 'init'=>'ক', 'txns'=>124, 'sales'=>'৪৫,০০০', 'cash'=>'২৫,৪০০', 'diff'=>'+৩৫০', 'status'=>'ok'],
            ['name'=>'Jamal', 'init'=>'জ', 'txns'=>36, 'sales'=>'২২,০০০', 'cash'=>'১০,৮৫০', 'diff'=>'−৫০', 'status'=>'warn'],
        ];
        @endphp

        @foreach($cashiers as $c)
        <div class="rounded-xl border border-gray-100 bg-gradient-to-br from-white to-gray-50/50 p-4 hover:shadow-md transition">
            <div class="flex items-start justify-between mb-3">
                <div class="flex items-center gap-3">
                    <div class="w-11 h-11 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-700 text-white flex items-center justify-center font-bold shadow-sm">
                        {{ $c['init'] }}
                    </div>
                    <div>
                        <p class="font-semibold text-gray-800">{{ $c['name'] }}</p>
                        <p class="text-xs text-gray-500">{{ $c['txns'] }} transactions</p>
                    </div>
                </div>
                @if($c['status'] == 'ok')
                    <span class="badge badge-success">✓ Matched</span>
                @else
                    <span class="badge badge-warning">⚠ Check</span>
                @endif
            </div>
            <div class="grid grid-cols-3 gap-2 text-center py-2 border-t border-b border-dashed border-gray-200">
                <div>
                    <p class="text-[10px] uppercase text-gray-500 font-semibold">Sales</p>
                    <p class="text-sm font-bold num mt-0.5">৳ {{ $c['sales'] }}</p>
                </div>
                <div>
                    <p class="text-[10px] uppercase text-gray-500 font-semibold">Cash</p>
                    <p class="text-sm font-bold num mt-0.5">৳ {{ $c['cash'] }}</p>
                </div>
                <div>
                    <p class="text-[10px] uppercase text-gray-500 font-semibold">Diff</p>
                    <p class="text-sm font-bold num mt-0.5 {{ str_starts_with($c['diff'], '−') ? 'text-rose-600' : (str_starts_with($c['diff'], '+') ? 'text-amber-600' : 'text-emerald-600') }}">
                        ৳ {{ $c['diff'] }}
                    </p>
                </div>
            </div>
            <div class="flex gap-2 mt-3">
                <button class="flex-1 text-xs py-1.5 rounded-lg border border-gray-200 text-gray-700 hover:bg-gray-50">View Log</button>
                <button class="flex-1 text-xs py-1.5 rounded-lg bg-indigo-600 text-white hover:bg-indigo-700">Approve</button>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- ===== SIGN-OFF SECTION ===== --}}
<div class="card !rounded-2xl bg-gradient-to-br from-slate-50 to-white border-slate-200">
    <div class="flex items-center gap-2 mb-5">
        <div class="w-8 h-8 rounded-lg bg-slate-200 flex items-center justify-center">✍️</div>
        <div>
            <h3 class="font-semibold text-gray-800">Day Close Sign-off</h3>
            <p class="text-xs text-gray-500">দিন বন্ধ করার আগে সই নিন</p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-6">
        <div>
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Cashier</p>
            <div class="h-20 rounded-xl border-2 border-dashed border-gray-200 flex items-center justify-center text-gray-300 text-sm italic">
                Sign here
            </div>
            <p class="text-xs text-gray-500 mt-2">Rahim Ahmed · Counter #1</p>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Supervisor</p>
            <div class="h-20 rounded-xl border-2 border-dashed border-gray-200 flex items-center justify-center text-gray-300 text-sm italic">
                Sign here
            </div>
            <p class="text-xs text-gray-500 mt-2">Jamal Hossain · Manager</p>
        </div>
        <div>
            <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider mb-2">Accountant</p>
            <div class="h-20 rounded-xl border-2 border-dashed border-gray-200 flex items-center justify-center text-gray-300 text-sm italic">
                Sign here
            </div>
            <p class="text-xs text-gray-500 mt-2">Pending review</p>
        </div>
    </div>

    <div class="mt-6 pt-4 border-t border-gray-100 flex flex-wrap gap-2 justify-end">
        <button class="btn btn-outline">💾 Save Draft</button>
        <button class="btn btn-outline">🖨 Print Report</button>
        <button class="btn btn-primary">🔒 Close Day & Lock</button>
    </div>
</div>

@push('styles')
<style>
    .pulse-dot { animation: pulse-dot 2s infinite; }
    @keyframes pulse-dot {
        0%,100% { opacity: 1; transform: scale(1); }
        50% { opacity: 0.5; transform: scale(0.85); }
    }
    .num { font-variant-numeric: tabular-nums; letter-spacing: -0.02em; }
    .grad-text {
        background: linear-gradient(135deg, #4f46e5 0%, #7c3aed 60%, #c026d3 100%);
        -webkit-background-clip: text; -webkit-text-fill-color: transparent; background-clip: text;
    }
</style>
@endpush

@endsection