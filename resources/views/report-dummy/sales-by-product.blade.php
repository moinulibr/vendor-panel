@extends('report-dummy.report')
@section('title', 'Sales by Product')
@section('page-title', 'Sales by Product')
@section('page-subtitle', 'প্রোডাক্ট-wise বিক্রয় বিশ্লেষণ')

@section('content')

<div class="card mb-6">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
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
            <select class="input"><option>All</option></select>
        </div>
        <div>
            <label class="label">Sort By</label>
            <select class="input">
                <option>Revenue (High to Low)</option>
                <option>Quantity</option>
                <option>Profit</option>
            </select>
        </div>
        <div class="flex items-end">
            <button class="btn btn-primary w-full justify-center">🔍 Apply</button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="card">
        <h3 class="font-semibold text-gray-800 mb-4">🏆 Top 10 Best Sellers</h3>
        <div class="space-y-3">
            @for($i = 1; $i <= 5; $i++)
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-indigo-100 text-indigo-600 flex items-center justify-center font-bold text-sm">{{ $i }}</div>
                <div class="flex-1">
                    <div class="flex justify-between text-sm">
                        <span class="font-medium">Product Name {{ $i }}</span>
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
        <h3 class="font-semibold text-gray-800 mb-4">📉 Bottom 10 (Slow Movers)</h3>
        <div class="space-y-3">
            @for($i = 1; $i <= 5; $i++)
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-bold text-sm">{{ $i }}</div>
                <div class="flex-1">
                    <div class="flex justify-between text-sm">
                        <span class="font-medium">Slow Product {{ $i }}</span>
                        <span class="font-semibold">৳ {{ 1200 - $i * 100 }}</span>
                    </div>
                    <div class="w-full bg-gray-100 rounded-full h-1.5 mt-1">
                        <div class="bg-red-500 h-1.5 rounded-full" style="width: {{ 20 - $i * 2 }}%"></div>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</div>

<div class="card">
    <h3 class="font-semibold text-gray-800 mb-4">Full Product Sales List</h3>
    <div class="overflow-x-auto">
        <table class="table-report">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th class="text-right">Qty Sold</th>
                    <th class="text-right">Revenue</th>
                    <th class="text-right">Cost</th>
                    <th class="text-right">Profit</th>
                    <th class="text-right">Margin %</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="font-mono text-xs">SKU-1024</td>
                    <td class="font-medium">Wireless Mouse</td>
                    <td>Electronics</td>
                    <td class="text-right">৩৪২</td>
                    <td class="text-right font-medium">৳ ১,৭১,০০০</td>
                    <td class="text-right">৳ ১,২০,০০০</td>
                    <td class="text-right text-green-600">৳ ৫১,০০০</td>
                    <td class="text-right">29.8%</td>
                </tr>
                <tr>
                    <td class="font-mono text-xs">SKU-1087</td>
                    <td class="font-medium">Bluetooth Speaker</td>
                    <td>Electronics</td>
                    <td class="text-right">২৮৫</td>
                    <td class="text-right font-medium">৳ ২,৮৫,০০০</td>
                    <td class="text-right">৳ ২,০০,০০০</td>
                    <td class="text-right text-green-600">৳ ৮৫,০০০</td>
                    <td class="text-right">29.8%</td>
                </tr>
                <tr>
                    <td class="font-mono text-xs">SKU-2011</td>
                    <td class="font-medium">Cotton T-Shirt</td>
                    <td>Fashion</td>
                    <td class="text-right">১,২৪০</td>
                    <td class="text-right font-medium">৳ ৬,২০,০০০</td>
                    <td class="text-right">৳ ৪,৩৪,০০০</td>
                    <td class="text-right text-green-600">৳ ১,৮৬,০০০</td>
                    <td class="text-right">30.0%</td>
                </tr>
                <tr>
                    <td class="font-mono text-xs">SKU-4012</td>
                    <td class="font-medium">Winter Jacket</td>
                    <td>Fashion</td>
                    <td class="text-right">৪২</td>
                    <td class="text-right font-medium">৳ ৮৪,০০০</td>
                    <td class="text-right">৳ ৬০,০০০</td>
                    <td class="text-right text-green-600">৳ ২৪,০০০</td>
                    <td class="text-right">28.6%</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection