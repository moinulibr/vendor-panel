@extends('report-dummy.report')

@section('title', 'Profit & Loss')
@section('page-title', 'Profit & Loss Statement')
@section('page-subtitle', 'আয়-ব্যয়ের সারসংক্ষেপ')

@section('content')

{{-- Filters --}}
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
                <option>Monthly</option>
                <option>Weekly</option>
                <option>Daily</option>
            </select>
        </div>
        <div class="flex items-end">
            <button class="btn btn-primary w-full justify-center">🔍 Generate</button>
        </div>
    </div>
</div>

{{-- Big Numbers --}}
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4 mb-6">
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Revenue</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">৳ ১২,৫০,০০০</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">COGS</p>
        <p class="text-2xl font-bold text-red-600 mt-1">৳ ৭,৮০,০০০</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Expenses</p>
        <p class="text-2xl font-bold text-orange-600 mt-1">৳ ২,১০,০০০</p>
    </div>
    <div class="stat-card bg-green-50 border-green-200">
        <p class="text-xs text-green-700 uppercase font-medium">Net Profit</p>
        <p class="text-2xl font-bold text-green-700 mt-1">৳ ২,৬০,০০০</p>
        <p class="text-xs text-green-600 mt-1">Margin: 20.8%</p>
    </div>
</div>

{{-- P&L Table --}}
<div class="card mb-6">
    <h3 class="font-semibold text-gray-800 mb-4">Statement Breakdown</h3>
    <table class="table-report">
        <tbody>
            <tr class="bg-gray-50 font-semibold">
                <td class="px-4 py-3">Revenue</td>
                <td class="px-4 py-3 text-right">৳ ১২,৫০,০০০</td>
            </tr>
            <tr>
                <td class="px-4 py-3 pl-8 text-gray-600">Product Sales</td>
                <td class="px-4 py-3 text-right">৳ ১১,৮০,০০০</td>
            </tr>
            <tr>
                <td class="px-4 py-3 pl-8 text-gray-600">Shipping Income</td>
                <td class="px-4 py-3 text-right">৳ ৫০,০০০</td>
            </tr>
            <tr>
                <td class="px-4 py-3 pl-8 text-gray-600">Other Income</td>
                <td class="px-4 py-3 text-right">৳ ২০,০০০</td>
            </tr>

            <tr class="bg-gray-50 font-semibold">
                <td class="px-4 py-3">Cost of Goods Sold</td>
                <td class="px-4 py-3 text-right text-red-600">- ৳ ৭,৮০,০০০</td>
            </tr>

            <tr class="bg-indigo-50 font-semibold">
                <td class="px-4 py-3">Gross Profit</td>
                <td class="px-4 py-3 text-right text-indigo-700">৳ ৪,৭০,০০০</td>
            </tr>

            <tr class="bg-gray-50 font-semibold">
                <td class="px-4 py-3">Operating Expenses</td>
                <td class="px-4 py-3 text-right text-red-600">- ৳ ২,১০,০০০</td>
            </tr>
            <tr>
                <td class="px-4 py-3 pl-8 text-gray-600">Salaries</td>
                <td class="px-4 py-3 text-right">৳ ১,২০,০০০</td>
            </tr>
            <tr>
                <td class="px-4 py-3 pl-8 text-gray-600">Rent</td>
                <td class="px-4 py-3 text-right">৳ ৪০,০০০</td>
            </tr>
            <tr>
                <td class="px-4 py-3 pl-8 text-gray-600">Marketing</td>
                <td class="px-4 py-3 text-right">৳ ৩০,০০০</td>
            </tr>
            <tr>
                <td class="px-4 py-3 pl-8 text-gray-600">Utilities</td>
                <td class="px-4 py-3 text-right">৳ ২০,০০০</td>
            </tr>

            <tr class="bg-green-100 font-bold text-green-800">
                <td class="px-4 py-3">Net Profit</td>
                <td class="px-4 py-3 text-right">৳ ২,৬০,০০০</td>
            </tr>
        </tbody>
    </table>
</div>

{{-- Chart --}}
<div class="card">
    <h3 class="font-semibold text-gray-800 mb-4">Monthly Trend</h3>
    <canvas id="plChart" height="90"></canvas>
</div>

@push('scripts')
<script>
new Chart(document.getElementById('plChart'), {
    type: 'bar',
    data: {
        labels: ['Sep','Oct','Nov','Dec','Jan'],
        datasets: [
            { label: 'Revenue', data: [850000, 920000, 1080000, 1150000, 1250000], backgroundColor: '#4f46e5' },
            { label: 'Expenses', data: [620000, 680000, 760000, 820000, 990000], backgroundColor: '#f87171' }
        ]
    },
    options: {
        responsive: true,
        plugins: { legend: { position: 'top' } },
        scales: {
            y: { beginAtZero: true, grid: { color: '#f3f4f6' } },
            x: { grid: { display: false } }
        }
    }
});
</script>
@endpush

@endsection