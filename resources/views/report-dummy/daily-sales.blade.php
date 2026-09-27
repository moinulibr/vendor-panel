@extends('report-dummy.report')

@section('title', 'Daily Sales Summary')
@section('page-title', 'Daily Sales Summary')
@section('page-subtitle', 'আজকের বিক্রয়ের পূর্ণ চিত্র')

@section('content')

{{-- Filters --}}
<div class="card mb-6">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div>
            <label class="label">From Date</label>
            <input type="date" class="input" value="2025-01-01">
        </div>
        <div>
            <label class="label">To Date</label>
            <input type="date" class="input" value="2025-01-31">
        </div>
        <div>
            <label class="label">Outlet</label>
            <select class="input">
                <option>All Outlets</option>
                <option>Main Store</option>
                <option>Online</option>
            </select>
        </div>
        <div>
            <label class="label">Channel</label>
            <select class="input">
                <option>All</option>
                <option>POS</option>
                <option>E-commerce</option>
            </select>
        </div>
        <div class="flex items-end">
            <button class="btn btn-primary w-full justify-center">🔍 Apply Filter</button>
        </div>
    </div>
</div>

{{-- Stat Cards --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium">Total Sales</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">৳ ১,২৫,৪০০</p>
                <p class="text-xs text-green-600 mt-1">▲ 12.5% vs yesterday</p>
            </div>
            <div class="w-12 h-12 bg-indigo-100 rounded-full flex items-center justify-center text-xl">💰</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium">Orders</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">৩৪২</p>
                <p class="text-xs text-green-600 mt-1">▲ 8.2%</p>
            </div>
            <div class="w-12 h-12 bg-blue-100 rounded-full flex items-center justify-center text-xl">🛒</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium">Avg Order Value</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">৳ ৩৬৭</p>
                <p class="text-xs text-red-600 mt-1">▼ 2.1%</p>
            </div>
            <div class="w-12 h-12 bg-green-100 rounded-full flex items-center justify-center text-xl">📈</div>
        </div>
    </div>
    <div class="stat-card">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium">Returns</p>
                <p class="text-2xl font-bold text-gray-800 mt-1">১২</p>
                <p class="text-xs text-gray-500 mt-1">৳ ৪,২০০</p>
            </div>
            <div class="w-12 h-12 bg-red-100 rounded-full flex items-center justify-center text-xl">↩️</div>
        </div>
    </div>
</div>

{{-- Chart + Payment Breakdown --}}
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mb-6">
    <div class="card lg:col-span-2">
        <h3 class="font-semibold text-gray-800 mb-4">Hourly Sales Trend</h3>
        <canvas id="salesChart" height="100"></canvas>
    </div>
    <div class="card">
        <h3 class="font-semibold text-gray-800 mb-4">Payment Methods</h3>
        <div class="space-y-3">
            <div>
                <div class="flex justify-between text-sm mb-1"><span>💵 Cash</span><span class="font-medium">৳ ৫২,০০০</span></div>
                <div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-green-500 h-2 rounded-full" style="width: 42%"></div></div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1"><span>💳 Card</span><span class="font-medium">৳ ৩৮,৪০০</span></div>
                <div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-blue-500 h-2 rounded-full" style="width: 31%"></div></div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1"><span>📱 bKash</span><span class="font-medium">৳ ২৫,০০০</span></div>
                <div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-pink-500 h-2 rounded-full" style="width: 20%"></div></div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1"><span>🏦 Bank</span><span class="font-medium">৳ ১০,০০০</span></div>
                <div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-purple-500 h-2 rounded-full" style="width: 7%"></div></div>
            </div>
        </div>
    </div>
</div>

{{-- Detail Table --}}
<div class="card">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-gray-800">Sales Detail</h3>
        <input type="text" placeholder="🔍 Search order id..." class="input max-w-xs">
    </div>
    <div class="overflow-x-auto">
        <table class="table-report">
            <thead>
                <tr>
                    <th>Order ID</th>
                    <th>Time</th>
                    <th>Channel</th>
                    <th>Cashier</th>
                    <th>Items</th>
                    <th>Payment</th>
                    <th class="text-right">Amount</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="font-mono text-xs">#ORD-10234</td>
                    <td>10:24 AM</td>
                    <td><span class="badge badge-info">POS</span></td>
                    <td>Rahim</td>
                    <td>3</td>
                    <td>Cash</td>
                    <td class="text-right font-medium">৳ ১,২৫০</td>
                    <td><span class="badge badge-success">Paid</span></td>
                </tr>
                <tr>
                    <td class="font-mono text-xs">#ORD-10235</td>
                    <td>10:31 AM</td>
                    <td><span class="badge badge-warning">Online</span></td>
                    <td>—</td>
                    <td>1</td>
                    <td>bKash</td>
                    <td class="text-right font-medium">৳ ৮৯০</td>
                    <td><span class="badge badge-success">Paid</span></td>
                </tr>
                <tr>
                    <td class="font-mono text-xs">#ORD-10236</td>
                    <td>10:45 AM</td>
                    <td><span class="badge badge-info">POS</span></td>
                    <td>Karim</td>
                    <td>5</td>
                    <td>Card</td>
                    <td class="text-right font-medium">৳ ৩,৪৫০</td>
                    <td><span class="badge badge-success">Paid</span></td>
                </tr>
                <tr>
                    <td class="font-mono text-xs">#ORD-10237</td>
                    <td>11:02 AM</td>
                    <td><span class="badge badge-warning">Online</span></td>
                    <td>—</td>
                    <td>2</td>
                    <td>Cash</td>
                    <td class="text-right font-medium">৳ ১,৭০০</td>
                    <td><span class="badge badge-danger">Refunded</span></td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="bg-gray-50 font-semibold">
                    <td colspan="6" class="px-4 py-3 text-right">Total:</td>
                    <td class="px-4 py-3 text-right">৳ ৭,২৯০</td>
                    <td></td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="flex items-center justify-between mt-4 text-sm">
        <p class="text-gray-500">Showing 1–4 of 342</p>
        <div class="flex gap-1">
            <button class="px-3 py-1 border rounded-lg hover:bg-gray-50">Prev</button>
            <button class="px-3 py-1 border rounded-lg bg-indigo-600 text-white">1</button>
            <button class="px-3 py-1 border rounded-lg hover:bg-gray-50">2</button>
            <button class="px-3 py-1 border rounded-lg hover:bg-gray-50">Next</button>
        </div>
    </div>
</div>

@push('scripts')
<script>
const ctx = document.getElementById('salesChart');
new Chart(ctx, {
    type: 'line',
    data: {
        labels: ['9AM','10AM','11AM','12PM','1PM','2PM','3PM','4PM','5PM','6PM','7PM','8PM'],
        datasets: [{
            label: 'Sales (৳)',
            data: [2500, 4800, 6200, 8900, 7200, 5400, 6800, 9200, 11500, 14200, 16800, 19400],
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
</script>
@endpush

@endsection