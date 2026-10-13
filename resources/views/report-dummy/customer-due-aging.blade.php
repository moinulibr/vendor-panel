@extends('report-dummy.report')
@section('title', 'Customer Due Aging')
@section('page-title', 'Customer Due Aging')
@section('page-subtitle', 'কে কত দিন ধরে বাকি — ঝুঁকি অনুযায়ী বিশ্লেষণ')

@section('content')

{{-- ===== FILTER BAR ===== --}}
<div class="card mb-6 !rounded-2xl">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
        <div>
            <label class="label">As of Date</label>
            <input type="date" class="input" value="2025-01-31">
        </div>
        <div>
            <label class="label">Customer</label>
            <select class="input"><option>All Customers</option></select>
        </div>
        <div>
            <label class="label">Min Due Amount</label>
            <input type="number" class="input" placeholder="0">
        </div>
        <div>
            <label class="label">Risk Level</label>
            <select class="input">
                <option>All</option>
                <option>Low</option>
                <option>Medium</option>
                <option>High</option>
                <option>Critical</option>
            </select>
        </div>
        <button class="btn btn-primary w-full justify-center !py-2.5">🔍 Apply Filter</button>
    </div>
</div>

{{-- ===== AGING BUCKETS (PREMIUM) ===== --}}
<div class="grid grid-cols-2 md:grid-cols-5 gap-4 mb-6">

    {{-- Current --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-emerald-50 via-white to-white border border-emerald-100 p-5 hover:shadow-lg transition-all duration-300 group">
        <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-emerald-100/60 group-hover:scale-110 transition-transform"></div>
        <div class="relative">
            <div class="flex items-center gap-2 mb-2">
                <span class="w-2 h-2 rounded-full bg-emerald-500 pulse-dot"></span>
                <p class="text-[11px] font-semibold text-emerald-700 uppercase tracking-wider">Current</p>
            </div>
            <p class="text-3xl font-extrabold text-emerald-700 num tracking-tight">৳ ৪,২৫,৩০০</p>
            <p class="text-xs text-emerald-600/80 mt-2">১৪২ জন কাস্টমার</p>
            <div class="mt-3 h-1.5 bg-emerald-100 rounded-full overflow-hidden">
                <div class="h-full bg-emerald-500 rounded-full" style="width: 42%"></div>
            </div>
            <p class="text-[10px] text-emerald-600/70 mt-1.5">৪২% of total due</p>
        </div>
    </div>

    {{-- 1-30 --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-yellow-50 via-white to-white border border-yellow-100 p-5 hover:shadow-lg transition-all duration-300 group">
        <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-yellow-100/60 group-hover:scale-110 transition-transform"></div>
        <div class="relative">
            <div class="flex items-center gap-2 mb-2">
                <span class="w-2 h-2 rounded-full bg-yellow-500 pulse-dot"></span>
                <p class="text-[11px] font-semibold text-yellow-700 uppercase tracking-wider">1 – 30 Days</p>
            </div>
            <p class="text-3xl font-extrabold text-yellow-700 num tracking-tight">৳ ২,৮৫,১০০</p>
            <p class="text-xs text-yellow-600/80 mt-2">৬৮ জন কাস্টমার</p>
            <div class="mt-3 h-1.5 bg-yellow-100 rounded-full overflow-hidden">
                <div class="h-full bg-yellow-500 rounded-full" style="width: 28%"></div>
            </div>
            <p class="text-[10px] text-yellow-600/70 mt-1.5">২৮% of total due</p>
        </div>
    </div>

    {{-- 31-60 --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-orange-50 via-white to-white border border-orange-100 p-5 hover:shadow-lg transition-all duration-300 group">
        <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-orange-100/60 group-hover:scale-110 transition-transform"></div>
        <div class="relative">
            <div class="flex items-center gap-2 mb-2">
                <span class="w-2 h-2 rounded-full bg-orange-500 pulse-dot"></span>
                <p class="text-[11px] font-semibold text-orange-700 uppercase tracking-wider">31 – 60 Days</p>
            </div>
            <p class="text-3xl font-extrabold text-orange-700 num tracking-tight">৳ ১,৬২,৪০০</p>
            <p class="text-xs text-orange-600/80 mt-2">৩৮ জন কাস্টমার</p>
            <div class="mt-3 h-1.5 bg-orange-100 rounded-full overflow-hidden">
                <div class="h-full bg-orange-500 rounded-full" style="width: 16%"></div>
            </div>
            <p class="text-[10px] text-orange-600/70 mt-1.5">১৬% of total due</p>
        </div>
    </div>

    {{-- 61-90 --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-rose-50 via-white to-white border border-rose-100 p-5 hover:shadow-lg transition-all duration-300 group">
        <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-rose-100/60 group-hover:scale-110 transition-transform"></div>
        <div class="relative">
            <div class="flex items-center gap-2 mb-2">
                <span class="w-2 h-2 rounded-full bg-rose-500 pulse-dot"></span>
                <p class="text-[11px] font-semibold text-rose-700 uppercase tracking-wider">61 – 90 Days</p>
            </div>
            <p class="text-3xl font-extrabold text-rose-700 num tracking-tight">৳ ৯৪,২০০</p>
            <p class="text-xs text-rose-600/80 mt-2">২১ জন কাস্টমার</p>
            <div class="mt-3 h-1.5 bg-rose-100 rounded-full overflow-hidden">
                <div class="h-full bg-rose-500 rounded-full" style="width: 9%"></div>
            </div>
            <p class="text-[10px] text-rose-600/70 mt-1.5">৯% of total due</p>
        </div>
    </div>

    {{-- 90+ --}}
    <div class="relative overflow-hidden rounded-2xl bg-gradient-to-br from-red-500 via-red-600 to-red-700 text-white p-5 hover:shadow-xl transition-all duration-300 group">
        <div class="absolute -right-6 -top-6 w-24 h-24 rounded-full bg-white/10 group-hover:scale-110 transition-transform"></div>
        <div class="absolute inset-0 bg-gradient-to-t from-black/10 to-transparent"></div>
        <div class="relative">
            <div class="flex items-center gap-2 mb-2">
                <span class="w-2 h-2 rounded-full bg-white pulse-dot"></span>
                <p class="text-[11px] font-semibold text-red-100 uppercase tracking-wider">90+ Days</p>
            </div>
            <p class="text-3xl font-extrabold num tracking-tight">৳ ৫২,০০০</p>
            <p class="text-xs text-red-100/90 mt-2">৯ জন কাস্টমার · Critical</p>
            <div class="mt-3 h-1.5 bg-white/20 rounded-full overflow-hidden">
                <div class="h-full bg-white rounded-full" style="width: 5%"></div>
            </div>
            <p class="text-[10px] text-red-100/80 mt-1.5">৫% of total due</p>
        </div>
    </div>
</div>

{{-- ===== AGING VISUALIZATION ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- Stacked Bar --}}
    <div class="lg:col-span-2 card !rounded-2xl">
        <div class="flex items-center justify-between mb-5">
            <div>
                <h3 class="font-semibold text-gray-800">Aging Distribution</h3>
                <p class="text-xs text-gray-500 mt-0.5">মোট বাকি ৳ ১০,১৯,০০০ — কোন বাকেটে কত</p>
            </div>
            <div class="text-right">
                <p class="text-xs text-gray-500">Total Due</p>
                <p class="text-2xl font-extrabold grad-text num">৳ ১০,১৯,০০০</p>
            </div>
        </div>

        {{-- Horizontal stacked bar --}}
        <div class="flex h-12 rounded-xl overflow-hidden ring-1 ring-gray-900/5">
            <div class="bg-gradient-to-b from-emerald-400 to-emerald-500 flex items-center justify-center text-white text-xs font-semibold" style="width: 42%">৪২%</div>
            <div class="bg-gradient-to-b from-yellow-400 to-yellow-500 flex items-center justify-center text-white text-xs font-semibold" style="width: 28%">২৮%</div>
            <div class="bg-gradient-to-b from-orange-400 to-orange-500 flex items-center justify-center text-white text-xs font-semibold" style="width: 16%">১৬%</div>
            <div class="bg-gradient-to-b from-rose-400 to-rose-500 flex items-center justify-center text-white text-xs font-semibold" style="width: 9%">৯%</div>
            <div class="bg-gradient-to-b from-red-600 to-red-700 flex items-center justify-center text-white text-xs font-semibold" style="width: 5%">৫%</div>
        </div>

        <div class="flex flex-wrap gap-4 mt-4 text-xs">
            <span class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-emerald-500"></span>Current</span>
            <span class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-yellow-500"></span>1–30</span>
            <span class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-orange-500"></span>31–60</span>
            <span class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-rose-500"></span>61–90</span>
            <span class="flex items-center gap-2"><span class="w-3 h-3 rounded bg-red-700"></span>90+</span>
        </div>

        {{-- Trend --}}
        <div class="mt-6 pt-5 border-t border-gray-100">
            <div class="flex items-center justify-between mb-3">
                <p class="text-xs font-semibold text-gray-600 uppercase tracking-wider">৬ মাসের ট্রেন্ড</p>
                <span class="badge badge-danger">▲ 18% vs last month</span>
            </div>
            <canvas id="agingTrend" height="60"></canvas>
        </div>
    </div>

    {{-- Risk Gauge --}}
    <div class="card !rounded-2xl flex flex-col">
        <h3 class="font-semibold text-gray-800 mb-1">Portfolio Risk Score</h3>
        <p class="text-xs text-gray-500 mb-4">সামগ্রিক কালেকশন ঝুঁকি</p>

        <div class="flex-1 flex items-center justify-center">
            <div class="relative w-40 h-40">
                <svg viewBox="0 0 100 100" class="w-full h-full -rotate-90">
                    <circle cx="50" cy="50" r="42" fill="none" stroke="#f3f4f6" stroke-width="10"/>
                    <circle cx="50" cy="50" r="42" fill="none" stroke="url(#riskGrad)" stroke-width="10"
                            stroke-dasharray="264" stroke-dashoffset="88" stroke-linecap="round"/>
                    <defs>
                        <linearGradient id="riskGrad" x1="0" y1="0" x2="1" y2="1">
                            <stop offset="0%" stop-color="#f59e0b"/>
                            <stop offset="100%" stop-color="#ef4444"/>
                        </linearGradient>
                    </defs>
                </svg>
                <div class="absolute inset-0 flex flex-col items-center justify-center">
                    <p class="text-3xl font-extrabold text-gray-800 num">৬৭</p>
                    <p class="text-[10px] uppercase tracking-wider text-gray-500 font-semibold">Medium-High</p>
                </div>
            </div>
        </div>

        <div class="grid grid-cols-3 gap-2 mt-4 pt-4 border-t border-gray-100 text-center">
            <div>
                <p class="text-lg font-bold text-red-600 num">৯</p>
                <p class="text-[10px] text-gray-500 uppercase">Critical</p>
            </div>
            <div>
                <p class="text-lg font-bold text-orange-600 num">৫৯</p>
                <p class="text-[10px] text-gray-500 uppercase">High</p>
            </div>
            <div>
                <p class="text-lg font-bold text-emerald-600 num">২১০</p>
                <p class="text-[10px] text-gray-500 uppercase">Healthy</p>
            </div>
        </div>
    </div>
</div>

{{-- ===== TOP DEFAULTERS ===== --}}
<div class="card !rounded-2xl mb-6">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h3 class="font-semibold text-gray-800 flex items-center gap-2">
                🚨 Top Defaulters
                <span class="badge badge-danger">Needs Action</span>
            </h3>
            <p class="text-xs text-gray-500 mt-0.5">সবচেয়ে বেশি দিন ধরে বাকি যাদের</p>
        </div>
        <button class="btn btn-outline text-xs">📧 Bulk Reminder</button>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
        @php
        $defaulters = [
            ['name'=>'Nasrin Akter', 'phone'=>'01711-000004', 'due'=>'৪২,৬০০', 'days'=>'১২৭', 'invoices'=>'৫'],
            ['name'=>'Hasan Mahmud', 'phone'=>'01711-000009', 'due'=>'৩৫,২০০', 'days'=>'৯৮', 'invoices'=>'৩'],
            ['name'=>'Rokeya Begum', 'phone'=>'01711-000015', 'due'=>'২৮,৯০০', 'days'=>'৯২', 'invoices'=>'৪'],
        ];
        @endphp

        @foreach($defaulters as $d)
        <div class="relative rounded-xl border border-red-200 bg-gradient-to-br from-red-50/60 to-white p-4 hover:shadow-md transition">
            <div class="flex items-start gap-3">
                <div class="w-11 h-11 rounded-full bg-gradient-to-br from-red-500 to-red-700 text-white flex items-center justify-center font-bold shadow-sm">
                    {{ mb_substr($d['name'], 0, 1) }}
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-semibold text-gray-800 truncate">{{ $d['name'] }}</p>
                    <p class="text-xs text-gray-500 truncate">{{ $d['phone'] }}</p>
                </div>
                <span class="badge badge-danger">{{ $d['days'] }}d</span>
            </div>
            <div class="mt-3 flex items-baseline justify-between">
                <p class="text-xl font-extrabold text-red-700 num">৳ {{ $d['due'] }}</p>
                <p class="text-xs text-gray-500">{{ $d['invoices'] }} invoices</p>
            </div>
            <div class="flex gap-2 mt-3">
                <button class="flex-1 text-xs py-1.5 rounded-lg bg-red-600 text-white font-medium hover:bg-red-700">📞 Call</button>
                <button class="flex-1 text-xs py-1.5 rounded-lg border border-gray-200 text-gray-700 font-medium hover:bg-gray-50">SMS</button>
            </div>
        </div>
        @endforeach
    </div>
</div>

{{-- ===== MAIN TABLE ===== --}}
<div class="card !rounded-2xl">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
        <div>
            <h3 class="font-semibold text-gray-800">Aging Detail Report</h3>
            <p class="text-xs text-gray-500 mt-0.5">২৭৮ জন কাস্টমারের বাকির বিস্তারিত</p>
        </div>
        <div class="flex gap-2">
            <button class="btn btn-outline text-xs">📧 Send Statement</button>
            <button class="btn btn-outline text-xs">📊 Export Excel</button>
            <button class="btn btn-primary text-xs">⬇️ PDF</button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table-report">
            <thead>
                <tr>
                    <th class="w-10"></th>
                    <th>Customer</th>
                    <th class="text-right">Total Due</th>
                    <th>Current</th>
                    <th>1–30</th>
                    <th>31–60</th>
                    <th>61–90</th>
                    <th>90+</th>
                    <th>Oldest</th>
                    <th>Last Payment</th>
                    <th>Risk</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                @php
                $rows = [
                    ['n'=>'Rahim Ahmed', 'd'=>'৫,৫০০', 'b'=>[0,0,5500,0,0], 'old'=>'২২d', 'last'=>'Jan 30', 'r'=>'Low', 'rc'=>'success'],
                    ['n'=>'Nasrin Akter', 'd'=>'৪২,৬০০', 'b'=>[0,0,0,12600,30000], 'old'=>'১২৭d', 'last'=>'Sep 12', 'r'=>'Critical', 'rc'=>'danger'],
                    ['n'=>'Karim Uddin', 'd'=>'২,০০০', 'b'=>[0,2000,0,0,0], 'old'=>'১৮d', 'last'=>'Jan 18', 'r'=>'Low', 'rc'=>'success'],
                    ['n'=>'Sadia Khan', 'd'=>'৮,৪০০', 'b'=>[0,0,8400,0,0], 'old'=>'৪৫d', 'last'=>'Dec 05', 'r'=>'Medium', 'rc'=>'warning'],
                    ['n'=>'Hasan Mahmud', 'd'=>'৩৫,২০০', 'b'=>[0,0,0,0,35200], 'old'=>'৯৮d', 'last'=>'Oct 03', 'r'=>'Critical', 'rc'=>'danger'],
                    ['n'=>'Rokeya Begum', 'd'=>'২৮,৯০০', 'b'=>[0,0,0,28900,0], 'old'=>'৯২d', 'last'=>'Oct 28', 'r'=>'High', 'rc'=>'danger'],
                    ['n'=>'Jamal Hossain', 'd'=>'১২,৩০০', 'b'=>[12300,0,0,0,0], 'old'=>'০d', 'last'=>'Jan 28', 'r'=>'Low', 'rc'=>'success'],
                ];
                @endphp

                @foreach($rows as $row)
                <tr class="group">
                    <td>
                        <input type="checkbox" class="rounded border-gray-300">
                    </td>
                    <td>
                        <div class="flex items-center gap-3">
                            <div class="w-9 h-9 rounded-full bg-gradient-to-br from-indigo-500 to-indigo-700 text-white flex items-center justify-center font-semibold text-xs">
                                {{ mb_substr($row['n'], 0, 1) }}
                            </div>
                            <div>
                                <p class="font-medium text-gray-800">{{ $row['n'] }}</p>
                                <p class="text-xs text-gray-400">ID #{{ rand(1000,9999) }}</p>
                            </div>
                        </div>
                    </td>
                    <td class="text-right font-bold text-gray-800 num">৳ {{ $row['d'] }}</td>

                    {{-- Bucket cells with heat --}}
                    @php
                    $bucketColors = ['emerald', 'yellow', 'orange', 'rose', 'red'];
                    $bucketLabels = ['Current','1–30','31–60','61–90','90+'];
                    @endphp
                    @foreach($row['b'] as $i => $val)
                    <td>
                        @if($val > 0)
                            <div class="inline-flex flex-col">
                                <span class="font-semibold text-{{ $bucketColors[$i] }}-700 num text-sm">৳ {{ number_format($val) }}</span>
                                <div class="h-1 bg-{{ $bucketColors[$i] }}-100 rounded-full mt-1">
                                    <div class="h-full bg-{{ $bucketColors[$i] }}-500 rounded-full" style="width: {{ min(100, $val/500) }}%"></div>
                                </div>
                            </div>
                        @else
                            <span class="text-gray-300">—</span>
                        @endif
                    </td>
                    @endforeach

                    <td>
                        <span class="badge {{ $row['old'] == '০d' ? 'badge-info' : (intval($row['old']) > 60 ? 'badge-danger' : 'badge-warning') }}">
                            {{ $row['old'] }}
                        </span>
                    </td>
                    <td class="text-xs text-gray-600">{{ $row['last'] }}</td>
                    <td>
                        <span class="badge badge-{{ $row['rc'] }}">{{ $row['r'] }}</span>
                    </td>
                    <td class="text-right">
                        <div class="flex justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button class="w-7 h-7 rounded-lg hover:bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs" title="Call">📞</button>
                            <button class="w-7 h-7 rounded-lg hover:bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs" title="SMS">✉️</button>
                            <button class="w-7 h-7 rounded-lg hover:bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs" title="Ledger">📒</button>
                            <button class="w-7 h-7 rounded-lg hover:bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs" title="Payment">💰</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray-50 border-t-2 border-gray-200">
                    <td colspan="2" class="px-4 py-3 font-bold text-gray-700">TOTAL</td>
                    <td class="px-4 py-3 text-right font-extrabold text-gray-900 num">৳ ১,৩৪,৯০০</td>
                    <td class="px-4 py-3 font-semibold text-emerald-700 num">৳ ১২,৩০০</td>
                    <td class="px-4 py-3 font-semibold text-yellow-700 num">৳ ২,০০০</td>
                    <td class="px-4 py-3 font-semibold text-orange-700 num">৳ ১৩,৯০০</td>
                    <td class="px-4 py-3 font-semibold text-rose-700 num">৳ ৪১,৫০০</td>
                    <td class="px-4 py-3 font-semibold text-red-700 num">৳ ৬৫,২০০</td>
                    <td colspan="4"></td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="flex items-center justify-between mt-5 text-sm">
        <p class="text-gray-500">Showing 1–7 of 278 customers</p>
        <div class="flex gap-1">
            <button class="w-9 h-9 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50">‹</button>
            <button class="w-9 h-9 rounded-lg bg-indigo-600 text-white font-medium shadow-sm">1</button>
            <button class="w-9 h-9 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50">2</button>
            <button class="w-9 h-9 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50">3</button>
            <span class="w-9 h-9 flex items-center justify-center text-gray-400">…</span>
            <button class="w-9 h-9 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50">40</button>
            <button class="w-9 h-9 rounded-lg border border-gray-200 text-gray-600 hover:bg-gray-50">›</button>
        </div>
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

@push('scripts')
<script>
new Chart(document.getElementById('agingTrend'), {
    type: 'line',
    data: {
        labels: ['Aug','Sep','Oct','Nov','Dec','Jan'],
        datasets: [
            { label: 'Total Due', data: [780000, 840000, 890000, 950000, 1010000, 1019000],
              borderColor: '#4f46e5', backgroundColor: 'rgba(79,70,229,0.08)', fill: true, tension: 0.4, pointRadius: 3 },
            { label: '90+ Critical', data: [22000, 28000, 34000, 41000, 47000, 52000],
              borderColor: '#dc2626', backgroundColor: 'rgba(220,38,38,0.06)', fill: true, tension: 0.4, pointRadius: 3 }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'bottom', labels: { boxWidth: 10, font: { size: 11 } } } },
        scales: { y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { callback: v => '৳' + (v/1000) + 'k', font: { size: 10 } } },
                   x: { grid: { display: false }, ticks: { font: { size: 10 } } } }
    }
});
</script>
@endpush

@endsection