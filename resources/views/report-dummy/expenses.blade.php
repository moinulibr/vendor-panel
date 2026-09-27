@extends('report-dummy.report')
@section('title', 'Expense Report')
@section('page-title', 'Expense Report')
@section('page-subtitle', 'খরচের বিশ্লেষণ')

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
            <label class="label">Category</label>
            <select class="input">
                <option>All Categories</option>
                <option>Salary</option>
                <option>Rent</option>
                <option>Marketing</option>
                <option>Utilities</option>
            </select>
        </div>
        <div class="flex items-end">
            <button class="btn btn-primary w-full justify-center">🔍 Filter</button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Total Expenses</p>
        <p class="text-2xl font-bold text-red-600 mt-1">৳ ২,১০,০০০</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Biggest Category</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">Salary</p>
        <p class="text-xs text-gray-500 mt-1">৳ ১,২০,০০০ (57%)</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">vs Last Month</p>
        <p class="text-2xl font-bold text-red-600 mt-1">▲ 8%</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Transactions</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">১৪৫</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="card">
        <h3 class="font-semibold text-gray-800 mb-4">Expense by Category</h3>
        <canvas id="expenseChart" height="120"></canvas>
    </div>
    <div class="card">
        <h3 class="font-semibold text-gray-800 mb-4">Category Breakdown</h3>
        <div class="space-y-3">
            <div class="flex justify-between text-sm"><span>Salary</span><span class="font-semibold">৳ ১,২০,০০০</span></div>
            <div class="flex justify-between text-sm"><span>Rent</span><span class="font-semibold">৳ ৪০,০০০</span></div>
            <div class="flex justify-between text-sm"><span>Marketing</span><span class="font-semibold">৳ ৩০,০০০</span></div>
            <div class="flex justify-between text-sm"><span>Utilities</span><span class="font-semibold">৳ ২০,০০০</span></div>
        </div>
    </div>
</div>

<div class="card">
    <h3 class="font-semibold text-gray-800 mb-4">Expense Transactions</h3>
    <div class="overflow-x-auto">
        <table class="table-report">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>Category</th>
                    <th>Description</th>
                    <th>Paid To</th>
                    <th>Method</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Jan 05, 2025</td>
                    <td><span class="badge badge-info">Salary</span></td>
                    <td>Staff salary - January</td>
                    <td>12 employees</td>
                    <td>Bank</td>
                    <td class="text-right font-medium">৳ ১,২০,০০০</td>
                </tr>
                <tr>
                    <td>Jan 03, 2025</td>
                    <td><span class="badge badge-warning">Rent</span></td>
                    <td>Shop rent - January</td>
                    <td>Landlord</td>
                    <td>Bank</td>
                    <td class="text-right font-medium">৳ ৪০,০০০</td>
                </tr>
                <tr>
                    <td>Jan 10, 2025</td>
                    <td><span class="badge badge-success">Marketing</span></td>
                    <td>Facebook Ads</td>
                    <td>Meta</td>
                    <td>Card</td>
                    <td class="text-right font-medium">৳ ৩০,০০০</td>
                </tr>
                <tr>
                    <td>Jan 15, 2025</td>
                    <td><span class="badge badge-info">Utilities</span></td>
                    <td>Electricity bill</td>
                    <td>DESCO</td>
                    <td>Cash</td>
                    <td class="text-right font-medium">৳ ২০,০০০</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@push('scripts')
<script>
new Chart(document.getElementById('expenseChart'), {
    type: 'doughnut',
    data: {
        labels: ['Salary','Rent','Marketing','Utilities'],
        datasets: [{
            data: [57, 19, 14, 10],
            backgroundColor: ['#4f46e5','#f59e0b','#10b981','#ef4444']
        }]
    },
    options: { responsive: true, plugins: { legend: { position: 'bottom' } } }
});
</script>
@endpush

@endsection