@extends('report-dummy.report')
@section('title', 'Purchase Orders')
@section('page-title', 'Purchase Order Report')
@section('page-subtitle', 'সাপ্লাই চেইন ও প্রোকিউরমেন্ট ট্র্যাকিং')

@section('content')

{{-- ===== FILTER ===== --}}
<div class="card !rounded-2xl mb-6">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4 items-end">
        <div>
            <label class="label">From</label>
            <input type="date" class="input" value="2025-01-01">
        </div>
        <div>
            <label class="label">To</label>
            <input type="date" class="input" value="2025-01-31">
        </div>
        <div>
            <label class="label">Supplier</label>
            <select class="input"><option>All Suppliers</option></select>
        </div>
        <div>
            <label class="label">Status</label>
            <select class="input">
                <option>All</option>
                <option>Draft</option>
                <option>Approved</option>
                <option>Sent</option>
                <option>Partially Received</option>
                <option>Received</option>
                <option>Overdue</option>
            </select>
        </div>
        <button class="btn btn-primary w-full justify-center !py-2.5">🔍 Filter</button>
    </div>
</div>

{{-- ===== TOP STATS ===== --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="relative rounded-2xl bg-white ring-1 ring-gray-900/5 p-5 shadow-[0_1px_2px_rgba(16,24,40,0.04),0_8px_24px_-12px_rgba(16,24,40,0.08)] overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-indigo-100 to-indigo-200/50 rounded-bl-full"></div>
        <div class="relative">
            <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Total POs</p>
            <p class="text-3xl font-extrabold text-gray-800 num mt-1">১৪৫</p>
            <p class="text-xs text-emerald-600 mt-2 flex items-center gap-1">▲ 12 this month</p>
        </div>
    </div>

    <div class="relative rounded-2xl bg-white ring-1 ring-gray-900/5 p-5 shadow-[0_1px_2px_rgba(16,24,40,0.04),0_8px_24px_-12px_rgba(16,24,40,0.08)] overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-amber-100 to-amber-200/50 rounded-bl-full"></div>
        <div class="relative">
            <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Pending Delivery</p>
            <p class="text-3xl font-extrabold text-amber-600 num mt-1">৩৮</p>
            <p class="text-xs text-gray-500 mt-2">৳ ৪,২৫,০০০ value</p>
        </div>
    </div>

    <div class="relative rounded-2xl bg-white ring-1 ring-gray-900/5 p-5 shadow-[0_1px_2px_rgba(16,24,40,0.04),0_8px_24px_-12px_rgba(16,24,40,0.08)] overflow-hidden">
        <div class="absolute top-0 right-0 w-20 h-20 bg-gradient-to-br from-rose-100 to-rose-200/50 rounded-bl-full"></div>
        <div class="relative">
            <p class="text-[11px] font-semibold text-gray-500 uppercase tracking-wider">Overdue POs</p>
            <p class="text-3xl font-extrabold text-rose-600 num mt-1">৬</p>
            <p class="text-xs text-rose-500 mt-2 flex items-center gap-1">⚠️ Needs followup</p>
        </div>
    </div>

    <div class="relative rounded-2xl bg-gradient-to-br from-indigo-600 to-indigo-800 text-white p-5 overflow-hidden shadow-lg">
        <div class="absolute -right-8 -top-8 w-32 h-32 bg-white/10 rounded-full"></div>
        <div class="absolute -right-4 -bottom-8 w-24 h-24 bg-white/5 rounded-full"></div>
        <div class="relative">
            <p class="text-[11px] font-semibold text-indigo-100 uppercase tracking-wider">Total PO Value</p>
            <p class="text-3xl font-extrabold num mt-1">৳ ১৮,২৫,০০০</p>
            <p class="text-xs text-indigo-100/90 mt-2">YTD · January 2025</p>
        </div>
    </div>
</div>

{{-- ===== STATUS FLOW (KANBAN MINI) ===== --}}
<div class="card !rounded-2xl mb-6">
    <div class="flex items-center justify-between mb-5">
        <div>
            <h3 class="font-semibold text-gray-800">PO Pipeline</h3>
            <p class="text-xs text-gray-500 mt-0.5">প্রতিটা স্টেজে কতগুলো PO আছে</p>
        </div>
        <button class="btn btn-primary text-xs">+ New Purchase Order</button>
    </div>

    <div class="grid grid-cols-2 md:grid-cols-6 gap-3">
        @php
        $stages = [
            ['label'=>'Draft', 'count'=>8, 'color'=>'gray', 'value'=>'৳ ৮২,০০০'],
            ['label'=>'Approved', 'count'=>14, 'color'=>'blue', 'value'=>'৳ ২,১০,০০০'],
            ['label'=>'Sent', 'count'=>22, 'color'=>'indigo', 'value'=>'৳ ৪,৮৫,০০০'],
            ['label'=>'Partial', 'count'=>9, 'color'=>'amber', 'value'=>'৳ ১,৭৫,০০০'],
            ['label'=>'Received', 'count'=>86, 'color'=>'emerald', 'value'=>'৳ ৯,৪০,০০০'],
            ['label'=>'Overdue', 'count'=>6, 'color'=>'rose', 'value'=>'৳ ৯৩,০০০'],
        ];
        @endphp
        @foreach($stages as $s)
        <div class="relative rounded-xl border border-{{ $s['color'] }}-100 bg-{{ $s['color'] }}-50/50 p-4 hover:shadow-md transition cursor-pointer group">
            <div class="flex items-center gap-2 mb-2">
                <span class="w-1.5 h-1.5 rounded-full bg-{{ $s['color'] }}-500"></span>
                <p class="text-[10px] font-bold text-{{ $s['color'] }}-700 uppercase tracking-wider">{{ $s['label'] }}</p>
            </div>
            <p class="text-2xl font-extrabold text-{{ $s['color'] }}-700 num">{{ $s['count'] }}</p>
            <p class="text-[10px] text-{{ $s['color'] }}-600/80 mt-1 num">{{ $s['value'] }}</p>
            <div class="absolute right-3 top-3 text-{{ $s['color'] }}-400 opacity-0 group-hover:opacity-100 transition">→</div>
        </div>
        @endforeach
    </div>
</div>

{{-- ===== SUPPLIER PERFORMANCE + TREND ===== --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">

    {{-- Trend Chart --}}
    <div class="lg:col-span-2 card !rounded-2xl">
        <div class="flex items-center justify-between mb-4">
            <div>
                <h3 class="font-semibold text-gray-800">PO Value Trend</h3>
                <p class="text-xs text-gray-500 mt-0.5">গত ৬ মাসের ক্রয় অর্ডার</p>
            </div>
            <select class="text-xs border rounded-lg px-2 py-1 text-gray-600">
                <option>Last 6 months</option>
                <option>Last 12 months</option>
            </select>
        </div>
        <canvas id="poTrend" height="90"></canvas>
    </div>

    {{-- Top Suppliers --}}
    <div class="card !rounded-2xl">
        <h3 class="font-semibold text-gray-800 mb-1">Top Suppliers</h3>
        <p class="text-xs text-gray-500 mb-4">এই মাসে সবচেয়ে বেশি PO</p>

        <div class="space-y-4">
            @php
            $suppliers = [
                ['name'=>'TechZone BD', 'pos'=>24, 'value'=>'৫,৪০,০০০', 'on_time'=>96],
                ['name'=>'Fashion Hub', 'pos'=>18, 'value'=>'৩,৮৫,০০০', 'on_time'=>92],
                ['name'=>'Gadget Palace', 'pos'=>12, 'value'=>'২,১০,০০০', 'on_time'=>78],
                ['name'=>'HomeMart', 'pos'=>8, 'value'=>'১,৪০,০০০', 'on_time'=>88],
            ];
            @endphp
            @foreach($suppliers as $i => $s)
            <div>
                <div class="flex items-center justify-between mb-1.5">
                    <div class="flex items-center gap-2">
                        <span class="w-5 h-5 rounded-md bg-indigo-100 text-indigo-700 text-[10px] font-bold flex items-center justify-center">{{ $i+1 }}</span>
                        <span class="text-sm font-medium text-gray-700">{{ $s['name'] }}</span>
                    </div>
                    <span class="text-xs font-semibold text-gray-700 num">৳ {{ $s['value'] }}</span>
                </div>
                <div class="flex items-center gap-2">
                    <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                        <div class="h-full rounded-full {{ $s['on_time'] >= 90 ? 'bg-emerald-500' : ($s['on_time'] >= 80 ? 'bg-amber-500' : 'bg-rose-500') }}"
                             style="width: {{ $s['on_time'] }}%"></div>
                    </div>
                    <span class="text-[10px] font-semibold text-gray-500 w-10 text-right">{{ $s['on_time'] }}%</span>
                </div>
                <p class="text-[10px] text-gray-400 mt-1">{{ $s['pos'] }} purchase orders · on-time delivery</p>
            </div>
            @endforeach
        </div>
    </div>
</div>

{{-- ===== MAIN PO TABLE ===== --}}
<div class="card !rounded-2xl">
    <div class="flex flex-wrap items-center justify-between gap-3 mb-5">
        <div>
            <h3 class="font-semibold text-gray-800">Purchase Order List</h3>
            <p class="text-xs text-gray-500 mt-0.5">১৪৫টি PO এর মধ্যে এই মাসের</p>
        </div>
        <div class="flex gap-2">
            <input type="text" placeholder="🔍 Search PO # or supplier..." class="input max-w-xs text-sm">
            <button class="btn btn-outline text-xs">📤 Export</button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table-report">
            <thead>
                <tr>
                    <th>PO Number</th>
                    <th>Supplier</th>
                    <th>Order Date</th>
                    <th>Expected</th>
                    <th>Items</th>
                    <th class="text-right">Value</th>
                    <th class="w-40">Received</th>
                    <th>Status</th>
                    <th class="text-right">Actions</th>
                </tr>
            </thead>
            <tbody>
                @php
                $pos = [
                    ['no'=>'PO-2025-0145','sup'=>'TechZone BD','ord'=>'Jan 28','exp'=>'Feb 04','items'=>12,'val'=>'৪৫,২০০','recv'=>0,'st'=>'Sent','stc'=>'info'],
                    ['no'=>'PO-2025-0144','sup'=>'Fashion Hub','ord'=>'Jan 25','exp'=>'Jan 30','items'=>8,'val'=>'২৮,৫০০','recv'=>100,'st'=>'Received','stc'=>'success'],
                    ['no'=>'PO-2025-0143','sup'=>'Gadget Palace','ord'=>'Jan 22','exp'=>'Jan 29','items'=>15,'val'=>'৬২,০০০','recv'=>60,'st'=>'Partial','stc'=>'warning'],
                    ['no'=>'PO-2025-0142','sup'=>'HomeMart','ord'=>'Jan 18','exp'=>'Jan 25','items'=>22,'val'=>'৩৮,৭৫০','recv'=>0,'st'=>'Overdue','stc'=>'danger'],
                    ['no'=>'PO-2025-0141','sup'=>'TechZone BD','ord'=>'Jan 15','exp'=>'Jan 22','items'=>6,'val'=>'১৮,২০০','recv'=>100,'st'=>'Received','stc'=>'success'],
                    ['no'=>'PO-2025-0140','sup'=>'Fashion Hub','ord'=>'Jan 12','exp'=>'Jan 20','items'=>18,'val'=>'৫২,৪০০','recv'=>100,'st'=>'Received','stc'=>'success'],
                    ['no'=>'PO-2025-0139','sup'=>'Gadget Palace','ord'=>'Jan 08','exp'=>'Jan 18','items'=>9,'val'=>'৩৪,৮০০','recv'=>0,'st'=>'Approved','stc'=>'info'],
                ];
                @endphp

                @foreach($pos as $p)
                <tr class="group">
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="w-8 h-8 rounded-lg bg-indigo-50 flex items-center justify-center text-xs">📄</div>
                            <span class="font-mono text-xs font-semibold text-indigo-700">{{ $p['no'] }}</span>
                        </div>
                    </td>
                    <td class="font-medium text-gray-800">{{ $p['sup'] }}</td>
                    <td class="text-xs text-gray-600">{{ $p['ord'] }}</td>
                    <td class="text-xs {{ $p['st'] == 'Overdue' ? 'text-rose-600 font-semibold' : 'text-gray-600' }}">
                        {{ $p['exp'] }}
                        @if($p['st'] == 'Overdue')<span class="text-[10px]">(৩ দিন দেরি)</span>@endif
                    </td>
                    <td class="text-xs text-gray-600">{{ $p['items'] }} items</td>
                    <td class="text-right font-bold text-gray-800 num">৳ {{ $p['val'] }}</td>
                    <td>
                        <div class="flex items-center gap-2">
                            <div class="flex-1 h-1.5 bg-gray-100 rounded-full overflow-hidden">
                                <div class="h-full rounded-full transition-all
                                    {{ $p['recv'] == 100 ? 'bg-emerald-500' : ($p['recv'] > 0 ? 'bg-amber-500' : 'bg-gray-300') }}"
                                    style="width: {{ $p['recv'] }}%"></div>
                            </div>
                            <span class="text-[10px] font-bold text-gray-600 w-8 text-right">{{ $p['recv'] }}%</span>
                        </div>
                    </td>
                    <td>
                        <span class="badge badge-{{ $p['stc'] }}">{{ $p['st'] }}</span>
                    </td>
                    <td class="text-right">
                        <div class="flex justify-end gap-1 opacity-0 group-hover:opacity-100 transition-opacity">
                            <button class="w-7 h-7 rounded-lg hover:bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs" title="View">👁</button>
                            <button class="w-7 h-7 rounded-lg hover:bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs" title="Receive">📦</button>
                            <button class="w-7 h-7 rounded-lg hover:bg-indigo-50 text-indigo-600 flex items-center justify-center text-xs" title="Print">🖨</button>
                        </div>
                    </td>
                </tr>
                @endforeach
            </tbody>
            <tfoot>
                <tr class="bg-gray-50 border-t-2 border-gray-200">
                    <td colspan="5" class="px-4 py-3 font-bold text-gray-700">TOTAL (7 POs)</td>
                    <td class="px-4 py-3 text-right font-extrabold text-gray-900 num">৳ ২,৭৯,৮৫০</td>
                    <td colspan="3"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@push('styles')
<style>
    .num { font-variant-numeric: tabular-nums; letter-spacing: -0.02em; }
</style>
@endpush

@push('scripts')
<script>
new Chart(document.getElementById('poTrend'), {
    type: 'bar',
    data: {
        labels: ['Aug','Sep','Oct','Nov','Dec','Jan'],
        datasets: [{
            label: 'PO Value',
            data: [1250000, 1420000, 1680000, 1890000, 2100000, 1825000],
            backgroundColor: (ctx) => {
                const g = ctx.chart.ctx.createLinearGradient(0,0,0,300);
                g.addColorStop(0, '#6366f1');
                g.addColorStop(1, '#a5b4fc');
                return g;
            },
            borderRadius: 8,
            borderSkipped: false,
            barThickness: 36
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f3f4f6' }, ticks: { callback: v => '৳' + (v/100000).toFixed(1) + 'L', font: { size: 10 } } },
            x: { grid: { display: false }, ticks: { font: { size: 11 } } }
        }
    }
});
</script>
@endpush

@endsection