@extends('report-dummy.report')
@section('title', 'Sales by Channel')
@section('page-title', 'Sales by Channel')
@section('page-subtitle', 'POS vs Online vs Marketplace')

@section('content')

<div class="card mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="label">From</label>
            <input type="date" class="input" value="2025-01-01">
        </div>
        <div>
            <label class="label">To</label>
            <input type="date" class="input" value="2025-01-31">
        </div>
        <div>
            <label class="label">Group By</label>
            <select class="input">
                <option>Daily</option>
                <option>Weekly</option>
                <option>Monthly</option>
            </select>
        </div>
        <div class="flex items-end">
            <button class="btn btn-primary w-full justify-center">🔍 Apply</button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
    <div class="stat-card border-l-4 border-indigo-500">
        <p class="text-xs text-gray-500 uppercase font-medium">POS</p>
        <p class="text-2xl font-bold text-indigo-600 mt-1">৳ ৬,৫০,০০০</p>
        <p class="text-xs text-gray-500 mt-1">52% of total</p>
    </div>
    <div class="stat-card border-l-4 border-blue-500">
        <p class="text-xs text-gray-500 uppercase font-medium">Website</p>
        <p class="text-2xl font-bold text-blue-600 mt-1">৳ ৩,৫০,০০০</p>
        <p class="text-xs text-gray-500 mt-1">28% of total</p>
    </div>
    <div class="stat-card border-l-4 border-pink-500">
        <p class="text-xs text-gray-500 uppercase font-medium">Mobile App</p>
        <p class="text-2xl font-bold text-pink-600 mt-1">৳ ১,৫০,০০০</p>
        <p class="text-xs text-gray-500 mt-1">12% of total</p>
    </div>
    <div class="stat-card border-l-4 border-orange-500">
        <p class="text-xs text-gray-500 uppercase font-medium">Marketplace</p>
        <p class="text-2xl font-bold text-orange-600 mt-1">৳ ১,০০,০০০</p>
        <p class="text-xs text-gray-500 mt-1">8% of total</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="card lg:col-span-2">
        <h3 class="font-semibold text-gray-800 mb-4">Channel Trend</h3>
        <canvas id="channelChart" height="100"></canvas>
    </div>
    <div class="card">
        <h3 class="font-semibold text-gray-800 mb-4">Revenue Share</h3>
        <canvas id="pieChart" height="200"></canvas>
    </div>
</div>

<div class="card">
    <h3 class="font-semibold text-gray-800 mb-4">Channel Performance</h3>
    <div class="overflow-x-auto">
        <table class="table-report">
            <thead>
                <tr>
                    <th>Channel</th>
                    <th class="text-right">Orders</th>
                    <th class="text-right">Revenue</th>
                    <th class="text-right">AOV</th>
                    <th class="text-right">Returns</th>
                    <th class="text-right">Conversion</th>
                    <th class="text-right">Growth</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><span class="badge badge-info">POS</span></td>
                    <td class="text-right">১,৮৫০</td>
                    <td class="text-right font-medium">৳ ৬,৫০,০০০</td>
                    <td class="text-right">৳ ৩৫১</td>
                    <td class="text-right">৮</td>
                    <td class="text-right">—</td>
                    <td class="text-right text-green-600">▲ 12%</td>
                </tr>
                <tr>
                    <td><span class="badge badge-warning">Website</span></td>
                    <td class="text-right">৯২০</td>
                    <td class="text-right font-medium">৳ ৩,৫০,০০০</td>
                    <td class="text-right">৳ ৩৮০</td>
                    <td class="text-right">১৫</td>
                    <td class="text-right">3.2%</td>
                    <td class="text-right text-green-600">▲ 8%</td>
                </tr>
                <tr>
                    <td><span class="badge badge-info">Mobile App</span></td>
                    <td class="text-right">৪১০</td>
                    <td class="text-right font-medium">৳ ১,৫০,০০০</td>
                    <td class="text-right">৳ ৩৬৬</td>
                    <td class="text-right">৫</td>
                    <td class="text-right">5.1%</td>
                    <td class="text-right text-green-600">▲ 22%</td>
                </tr>
                <tr>
                    <td><span class="badge badge-warning">Marketplace</span></td>
                    <td class="text-right">২৮০</td>
                    <td class="text-right font-medium">৳ ১,০০,০০০</td>
                    <td class="text-right">৳ ৩৫৭</td>
                    <td class="text-right">১২</td>
                    <td class="text-right">—</td>
                    <td class="text-right text-red-600">▼ 5%</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
new Chart(document.getElementById('channelChart'), {
    type: 'line',
    data: {
        labels: ['Week 1','Week 2','Week 3','Week 4'],
        datasets: [
            { label: 'POS', data: [150000, 160000, 170000, 170000], borderColor: '#4f46e5', tension: 0.4 },
            { label: 'Website', data: [80000, 85000, 90000, 95000], borderColor: '#3b82f6', tension: 0.4 },
            { label: 'App', data: [30000, 35000, 40000, 45000], borderColor: '#ec4899', tension: 0.4 }
        ]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});

new Chart(document.getElementById('pieChart'), {
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