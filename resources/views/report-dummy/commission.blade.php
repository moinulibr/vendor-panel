@extends('report-dummy.report')
@section('title', 'Commission Report')
@section('page-title', 'Commission Report')
@section('page-subtitle', 'প্ল্যাটফর্মের কমিশন আয়')

@section('content')

<div class="card mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="label">Period</label>
            <input type="month" class="input" value="2025-01">
        </div>
        <div>
            <label class="label">Vendor</label>
            <select class="input"><option>All</option></select>
        </div>
        <div>
            <label class="label">Category</label>
            <select class="input"><option>All</option></select>
        </div>
        <div class="flex items-end">
            <button class="btn btn-primary w-full justify-center">🔍 Filter</button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Gross Sales</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">৳ ১২,৫০,০০০</p>
    </div>
    <div class="stat-card bg-green-50 border-green-200">
        <p class="text-xs text-green-700 uppercase font-medium">Commission Earned</p>
        <p class="text-2xl font-bold text-green-700 mt-1">৳ ১,৮৭,৫০০</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Avg Rate</p>
        <p class="text-2xl font-bold text-indigo-600 mt-1">15%</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Active Vendors</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">৪২</p>
    </div>
</div>

<div class="card">
    <h3 class="font-semibold text-gray-800 mb-4">Vendor Commission Breakdown</h3>
    <div class="overflow-x-auto">
        <table class="table-report">
            <thead>
                <tr>
                    <th>Vendor</th>
                    <th class="text-right">Orders</th>
                    <th class="text-right">Gross Sales</th>
                    <th class="text-right">Rate</th>
                    <th class="text-right">Commission</th>
                    <th class="text-right">Refunds</th>
                    <th class="text-right">Net Commission</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="font-medium">TechZone BD</td>
                    <td class="text-right">৩৪২</td>
                    <td class="text-right">৳ ২,৫০,০০০</td>
                    <td class="text-right">15%</td>
                    <td class="text-right font-medium text-green-600">৳ ৩৭,৫০০</td>
                    <td class="text-right text-red-600">- ৳ ৭৮০</td>
                    <td class="text-right font-semibold">৳ ৩৬,৭২০</td>
                </tr>
                <tr>
                    <td class="font-medium">Fashion Hub</td>
                    <td class="text-right">৪১২</td>
                    <td class="text-right">৳ ১,৮৫,০০০</td>
                    <td class="text-right">15%</td>
                    <td class="text-right font-medium text-green-600">৳ ২৭,৭৫০</td>
                    <td class="text-right text-red-600">- ৳ ৩১৫</td>
                    <td class="text-right font-semibold">৳ ২৭,৪৩৫</td>
                </tr>
                <tr>
                    <td class="font-medium">Gadget Palace</td>
                    <td class="text-right">১৮৫</td>
                    <td class="text-right">৳ ৯৫,০০০</td>
                    <td class="text-right">15%</td>
                    <td class="text-right font-medium text-green-600">৳ ১৪,২৫০</td>
                    <td class="text-right text-red-600">- ৳ ০</td>
                    <td class="text-right font-semibold">৳ ১৪,২৫০</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="bg-gray-50 font-semibold">
                    <td class="px-4 py-3">Total</td>
                    <td class="px-4 py-3 text-right">৯৩৯</td>
                    <td class="px-4 py-3 text-right">৳ ৫,৩০,০০০</td>
                    <td class="px-4 py-3 text-right">—</td>
                    <td class="px-4 py-3 text-right text-green-700">৳ ৭৯,৫০০</td>
                    <td class="px-4 py-3 text-right text-red-600">- ৳ ১,০৯৫</td>
                    <td class="px-4 py-3 text-right">৳ ৭৮,৪০৫</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@endsection