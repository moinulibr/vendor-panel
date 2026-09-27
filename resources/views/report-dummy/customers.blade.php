@extends('report-dummy.report')
@section('title', 'Customer Report')
@section('page-title', 'Customer Report')
@section('page-subtitle', 'কাস্টমার বিশ্লেষণ')

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
            <label class="label">Segment</label>
            <select class="input">
                <option>All</option>
                <option>New</option>
                <option>Returning</option>
                <option>VIP</option>
                <option>Inactive</option>
            </select>
        </div>
        <div class="flex items-end">
            <button class="btn btn-primary w-full justify-center">🔍 Filter</button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Total Customers</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">৪,২৪০</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">New (This Month)</p>
        <p class="text-2xl font-bold text-green-600 mt-1">+২৮৫</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Retention Rate</p>
        <p class="text-2xl font-bold text-indigo-600 mt-1">62%</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Avg CLV</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">৳ ৪,৮৫০</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="card">
        <h3 class="font-semibold text-gray-800 mb-4">New vs Returning</h3>
        <canvas id="customerChart" height="120"></canvas>
    </div>
    <div class="card">
        <h3 class="font-semibold text-gray-800 mb-4">Top 5 Customers</h3>
        <div class="space-y-3">
            @for($i = 1; $i <= 5; $i++)
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">{{ $i }}</div>
                <div class="flex-1">
                    <div class="flex justify-between text-sm">
                        <span class="font-medium">Customer {{ $i }}</span>
                        <span class="font-semibold">৳ {{ 85000 - $i * 8000 }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5 mt-1">
                        <div class="bg-indigo-500 h-1.5 rounded-full" style="width: {{ 100 - $i * 12 }}%"></div>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</div>

<div class="card">
    <h3 class="font-semibold text-gray-800 mb-4">Customer List</h3>
    <div class="overflow-x-auto">
        <table class="table-report">
            <thead>
                <tr>
                    <th>Customer</th>
                    <th>Email</th>
                    <th class="text-right">Orders</th>
                    <th class="text-right">Total Spent</th>
                    <th class="text-right">AOV</th>
                    <th>Last Order</th>
                    <th>Segment</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="font-medium">Rahim Ahmed</td>
                    <td class="text-gray-600">rahim@mail.com</td>
                    <td class="text-right">২৪</td>
                    <td class="text-right font-medium">৳ ৮৫,০০০</td>
                    <td class="text-right">৳ ৩,৫৪০</td>
                    <td>Jan 28, 2025</td>
                    <td><span class="badge badge-success">VIP</span></td>
                </tr>
                <tr>
                    <td class="font-medium">Sadia Khan</td>
                    <td class="text-gray-600">sadia@mail.com</td>
                    <td class="text-right">১৫</td>
                    <td class="text-right font-medium">৳ ৫২,০০০</td>
                    <td class="text-right">৳ ৩,৪৬৭</td>
                    <td>Jan 25, 2025</td>
                    <td><span class="badge badge-info">Returning</span></td>
                </tr>
                <tr>
                    <td class="font-medium">Karim Uddin</td>
                    <td class="text-gray-600">karim@mail.com</td>
                    <td class="text-right">১</td>
                    <td class="text-right font-medium">৳ ১,২০০</td>
                    <td class="text-right">৳ ১,২০০</td>
                    <td>Jan 20, 2025</td>
                    <td><span class="badge badge-warning">New</span></td>
                </tr>
                <tr>
                    <td class="font-medium">Nasrin Akter</td>
                    <td class="text-gray-600">nasrin@mail.com</td>
                    <td class="text-right">৮</td>
                    <td class="text-right font-medium">৳ ২৪,০০০</td>
                    <td class="text-right">৳ ৩,০০০</td>
                    <td>Sep 12, 2024</td>
                    <td><span class="badge badge-danger">Inactive</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
new Chart(document.getElementById('customerChart'), {
    type: 'bar',
    data: {
        labels: ['Sep','Oct','Nov','Dec','Jan'],
        datasets: [
            { label: 'New', data: [180, 220, 260, 300, 285], backgroundColor: '#4f46e5' },
            { label: 'Returning', data: [420, 480, 520, 580, 620], backgroundColor: '#10b981' }
        ]
    },
    options: { responsive: true, scales: { y: { beginAtZero: true } } }
});
</script>
@endpush

@endsection