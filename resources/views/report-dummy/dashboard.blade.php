@extends('report-dummy.report')
@section('title', 'Executive Dashboard')
@section('page-title', 'Executive Dashboard')
@section('page-subtitle', 'সবকিছু এক নজরে')

@section('content')

{{-- Top Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="stat-card bg-gradient-to-br from-indigo-500 to-indigo-600 text-white border-0">
        <p class="text-xs uppercase font-medium opacity-80">Today's Sales</p>
        <p class="text-3xl font-bold mt-1">৳ ১,২৫,৪০০</p>
        <p class="text-xs mt-2 opacity-90">▲ 12.5% vs yesterday</p>
    </div>
    <div class="stat-card bg-gradient-to-br from-green-500 to-green-600 text-white border-0">
        <p class="text-xs uppercase font-medium opacity-80">Today's Profit</p>
        <p class="text-3xl font-bold mt-1">৳ ২৬,০০০</p>
        <p class="text-xs mt-2 opacity-90">▲ 8.2%</p>
    </div>
    <div class="stat-card bg-gradient-to-br from-blue-500 to-blue-600 text-white border-0">
        <p class="text-xs uppercase font-medium opacity-80">Orders</p>
        <p class="text-3xl font-bold mt-1">৩৪২</p>
        <p class="text-xs mt-2 opacity-90">POS: 220 · Online: 122</p>
    </div>
    <div class="stat-card bg-gradient-to-br from-orange-500 to-orange-600 text-white border-0">
        <p class="text-xs uppercase font-medium opacity-80">Pending Actions</p>
        <p class="text-3xl font-bold mt-1">১৫</p>
        <p class="text-xs mt-2 opacity-90">7 payouts · 6 restock · 2 returns</p>
    </div>
</div>

{{-- Alerts --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 mb-6">
    <div class="card border-l-4 border-red-500">
        <div class="flex items-start gap-3">
            <div class="text-2xl">⚠️</div>
            <div>
                <p class="font-semibold text-gray-800">২৪টি প্রোডাক্ট লো স্টকে</p>
                <p class="text-xs text-gray-500 mt-0.5">৬টি সম্পূর্ণ শেষ</p>
                <a href="#" class="text-xs text-indigo-600 hover:underline mt-2 inline-block">View →</a>
            </div>
        </div>
    </div>
    <div class="card border-l-4 border-yellow-500">
        <div class="flex items-start gap-3">
            <div class="text-2xl">💸</div>
            <div>
                <p class="font-semibold text-gray-800">৭টি ভেন্ডর পেআউট বাকি</p>
                <p class="text-xs text-gray-500 mt-0.5">মোট ৳ ৪,৪০,২০০</p>
                <a href="#" class="text-xs text-indigo-600 hover:underline mt-2 inline-block">Process →</a>
            </div>
        </div>
    </div>
    <div class="card border-l-4 border-blue-500">
        <div class="flex items-start gap-3">
            <div class="text-2xl">↩️</div>
            <div>
                <p class="font-semibold text-gray-800">২টি রিটার্ন পেন্ডিং</p>
                <p class="text-xs text-gray-500 mt-0.5">৳ ১,৭০০ রিফান্ড</p>
                <a href="#" class="text-xs text-indigo-600 hover:underline mt-2 inline-block">Review →</a>
            </div>
        </div>
    </div>
</div>

{{-- Sales Trend Chart --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="card lg:col-span-2">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">Sales Trend (Last 7 Days)</h3>
            <select class="input max-w-xs text-sm">
                <option>Last 7 days</option>
                <option>Last 30 days</option>
            </select>
        </div>
        <canvas id="trendChart" height="100"></canvas>
    </div>
    <div class="card">
        <h3 class="font-semibold text-gray-800 mb-4">Channel Split</h3>
        <canvas id="dashPie" height="200"></canvas>
    </div>
</div>

{{-- Top Products + Top Vendors --}}
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">🏆 Top 5 Products</h3>
            <a href="#" class="text-xs text-indigo-600 hover:underline">View all</a>
        </div>
        <div class="space-y-3">
            @for($i = 1; $i <= 5; $i++)
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-xs">{{ $i }}</div>
                <div class="flex-1">
                    <div class="flex justify-between text-sm">
                        <span class="font-medium">Product {{ $i }}</span>
                        <span class="font-semibold">৳ {{ 45000 - $i * 3000 }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5 mt-1">
                        <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ 100 - $i * 12 }}%"></div>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>

    <div class="card">
        <div class="flex items-center justify-between mb-4">
            <h3 class="font-semibold text-gray-800">🥇 Top 5 Vendors</h3>
            <a href="#" class="text-xs text-indigo-600 hover:underline">View all</a>
        </div>
        <div class="space-y-3">
            @for($i = 1; $i <= 5; $i++)
            <div class="flex items-center gap-3">
                <div class="w-7 h-7 rounded-full bg-green-100 text-green-600 flex items-center justify-center font-bold text-xs">{{ $i }}</div>
                <div class="flex-1">
                    <div class="flex justify-between text-sm">
                        <span class="font-medium">Vendor {{ $i }}</span>
                        <span class="font-semibold">৳ {{ 250000 - $i * 30000 }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5 mt-1">
                        <div class="bg-green-500 h-1.5 rounded-full" style="width: {{ 100 - $i * 15 }}%"></div>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</div>

{{-- Recent Orders --}}
<div class="card">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-gray-800">Recent Orders</h3>
        <a href="#" class="text-xs text-indigo-600 hover:underline">View all</a>
    </div>
    <div class="overflow-x-auto">
        <table class="table-report">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Channel</th>
                    <th>Vendor</th>
                    <th class="text-right">Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="font-mono text-xs">#ORD-10240</td>
                    <td>Rahim Ahmed</td>
                    <td><span class="badge badge-info">POS</span></td>
                    <td>—</td>
                    <td class="text-right font-medium">৳ ৩,৪৫০</td>
                    <td><span class="badge badge-success">Paid</span></td>
                </tr>
                <tr>
                    <td class="font-mono text-xs">#ORD-10239</td>
                    <td>Sadia Khan</td>
                    <td><span class="badge badge-warning">Online</span></td>
                    <td>Fashion Hub</td>
                    <td class="text-right font-medium">৳ ১,৭০০</td>
                    <td><span class="badge badge-info">Processing</span></td>
                </tr>
                <tr>
                    <td class="font-mono text-xs">#ORD-10238</td>
                    <td>Karim Uddin</td>
                    <td><span class="badge badge-warning">Online</span></td>
                    <td>TechZone BD</td>
                    <td class="text-right font-medium">৳ ৮৯০</td>
                    <td><span class="badge badge-success">Paid</span></td>
                </tr>
                <tr>
                    <td class="font-mono text-xs">#ORD-10237</td>
                    <td>Nasrin Akter</td>
                    <td><span class="badge badge-info">POS</span></td>
                    <td>—</td>
                    <td class="text-right font-medium">৳ ২,২০০</td>
                    <td><span class="badge badge-danger">Refunded</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
new Chart(document.getElementById('trendChart'), {
    type: 'line',
    data: {
        labels: ['Mon','Tue','Wed','Thu','Fri','Sat','Sun'],
        datasets: [{
            label: 'Sales',
            data: [85000, 92000, 78000, 105000, 125000, 142000, 125400],
            borderColor: '#4f46e5',
            backgroundColor: 'rgba(79,70,229,0.08)',
            fill: true,
            tension: 0.4,
            pointRadius: 4,
            pointBackgroundColor: '#4f46e5'
        }]
    },
    options: {
        responsive: true,
        plugins: { legend: { display: false } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
            x: { grid: { display: false } }
        }
    }
});

new Chart(document.getElementById('dashPie'), {
    type: 'doughnut',
    data: {
        labels: ['POS','Website','App','Marketplace'],
        datasets: [{
            data: [52, 28, 12, 8],
            backgroundColor: ['#4f46e5','#3b82f6','#ec4899','#f97316']
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
</script>
@endpush

@endsection