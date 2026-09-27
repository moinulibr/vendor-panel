@extends('report-dummy.report')
@section('title', 'Stock Valuation')
@section('page-title', 'Stock Valuation Report')
@section('page-subtitle', 'ইনভেন্টরির মোট মূল্য')

@section('content')

<div class="card mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="label">As of Date</label>
            <input type="date" class="input" value="2025-01-31">
        </div>
        <div>
            <label class="label">Valuation Method</label>
            <select class="input">
                <option>FIFO</option>
                <option>Weighted Average</option>
                <option>LIFO</option>
            </select>
        </div>
        <div>
            <label class="label">Warehouse</label>
            <select class="input"><option>All</option></select>
        </div>
        <div class="flex items-end">
            <button class="btn btn-primary w-full justify-center">🔍 Generate</button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Total SKUs</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">১,২৪৫</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Total Units</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">১৮,৩৪০</p>
    </div>
    <div class="stat-card bg-indigo-50 border-indigo-200">
        <p class="text-xs text-indigo-700 uppercase font-medium">Cost Value</p>
        <p class="text-2xl font-bold text-indigo-700 mt-1">৳ ৩৫,৬০,০০০</p>
    </div>
    <div class="stat-card bg-green-50 border-green-200">
        <p class="text-xs text-green-700 uppercase font-medium">Retail Value</p>
        <p class="text-2xl font-bold text-green-700 mt-1">৳ ৫২,৪০,০০০</p>
    </div>
</div>

<div class="card">
    <h3 class="font-semibold text-gray-800 mb-4">Category-wise Valuation</h3>
    <div class="overflow-x-auto">
        <table class="table-report">
            <thead>
                <tr>
                    <th>Category</th>
                    <th class="text-right">SKUs</th>
                    <th class="text-right">Units</th>
                    <th class="text-right">Cost Value</th>
                    <th class="text-right">Retail Value</th>
                    <th class="text-right">Potential Profit</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="font-medium">Electronics</td>
                    <td class="text-right">৩৪০</td>
                    <td class="text-right">৪,২০০</td>
                    <td class="text-right">৳ ১৮,৫০,০০০</td>
                    <td class="text-right">৳ ২৬,৪০,০০০</td>
                    <td class="text-right text-green-600 font-medium">৳ ৭,৯০,০০০</td>
                </tr>
                <tr>
                    <td class="font-medium">Fashion</td>
                    <td class="text-right">৫৬০</td>
                    <td class="text-right">৮,৯০০</td>
                    <td class="text-right">৳ ৯,২০,০০০</td>
                    <td class="text-right">৳ ১৪,৫০,০০০</td>
                    <td class="text-right text-green-600 font-medium">৳ ৫,৩০,০০০</td>
                </tr>
                <tr>
                    <td class="font-medium">Accessories</td>
                    <td class="text-right">২৪৫</td>
                    <td class="text-right">৩,৫০০</td>
                    <td class="text-right">৳ ৫,১০,০০০</td>
                    <td class="text-right">৳ ৮,২০,০০০</td>
                    <td class="text-right text-green-600 font-medium">৳ ৩,১০,০০০</td>
                </tr>
                <tr>
                    <td class="font-medium">Home & Kitchen</td>
                    <td class="text-right">১০০</td>
                    <td class="text-right">১,৭৪০</td>
                    <td class="text-right">৳ ২,৮০,০০০</td>
                    <td class="text-right">৳ ৩,৩০,০০০</td>
                    <td class="text-right text-green-600 font-medium">৳ ৫০,০০০</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="bg-gray-50 font-semibold">
                    <td class="px-4 py-3">Total</td>
                    <td class="px-4 py-3 text-right">১,২৪৫</td>
                    <td class="px-4 py-3 text-right">১৮,৩৪০</td>
                    <td class="px-4 py-3 text-right">৳ ৩৫,৬০,০০০</td>
                    <td class="px-4 py-3 text-right">৳ ৫২,৪০,০০০</td>
                    <td class="px-4 py-3 text-right text-green-700">৳ ১৬,৮০,০০০</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@endsection