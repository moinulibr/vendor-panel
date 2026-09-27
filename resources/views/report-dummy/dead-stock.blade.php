@extends('report-dummy.report')
@section('title', 'Dead Stock')
@section('page-title', 'Dead / Slow-Moving Stock')
@section('page-subtitle', 'যেসব প্রোডাক্ট বিক্রি হচ্ছে না')

@section('content')

<div class="bg-yellow-50 border border-yellow-200 rounded-xl p-4 mb-6 flex items-start gap-3">
    <div class="text-2xl">💤</div>
    <div>
        <h3 class="font-semibold text-yellow-800">৩৮টি প্রোডাক্ট ৯০ দিনে বিক্রি হয়নি</h3>
        <p class="text-sm text-yellow-700 mt-0.5">৳ ৩,২০,০০০ ক্যাপিটাল আটকে আছে। ক্লিয়ারেন্স সেল বিবেচনা করুন।</p>
    </div>
</div>

<div class="card mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="label">Not Sold In (days)</label>
            <select class="input">
                <option>30 days</option>
                <option selected>60 days</option>
                <option>90 days</option>
                <option>180 days</option>
            </select>
        </div>
        <div>
            <label class="label">Category</label>
            <select class="input"><option>All</option></select>
        </div>
        <div>
            <label class="label">Min Stock Value</label>
            <input type="number" class="input" placeholder="0">
        </div>
        <div class="flex items-end">
            <button class="btn btn-primary w-full justify-center">🔍 Filter</button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="stat-card border-l-4 border-red-500">
        <p class="text-xs text-gray-500 uppercase font-medium">Dead Stock Value</p>
        <p class="text-2xl font-bold text-red-600 mt-1">৳ ৩,২০,০০০</p>
    </div>
    <div class="stat-card border-l-4 border-yellow-500">
        <p class="text-xs text-gray-500 uppercase font-medium">Total Units</p>
        <p class="text-2xl font-bold text-yellow-600 mt-1">৪৫৬</p>
    </div>
    <div class="stat-card border-l-4 border-blue-500">
        <p class="text-xs text-gray-500 uppercase font-medium">Products</p>
        <p class="text-2xl font-bold text-blue-600 mt-1">৩৮</p>
    </div>
</div>

<div class="card">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-gray-800">Dead Stock Items</h3>
        <button class="btn btn-primary">🏷️ Create Clearance Sale</button>
    </div>
    <div class="overflow-x-auto">
        <table class="table-report">
            <thead>
                <tr>
                    <th>SKU</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Vendor</th>
                    <th class="text-right">Stock</th>
                    <th class="text-right">Value</th>
                    <th class="text-right">Last Sold</th>
                    <th class="text-right">Days Idle</th>
                    <th>Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="font-mono text-xs">SKU-3050</td>
                    <td>Old Model Headphone</td>
                    <td>Electronics</td>
                    <td>TechZone BD</td>
                    <td class="text-right">42</td>
                    <td class="text-right font-medium">৳ ৮৪,০০০</td>
                    <td class="text-right">Sep 12, 2024</td>
                    <td class="text-right"><span class="badge badge-danger">126</span></td>
                    <td><button class="text-indigo-600 hover:underline text-sm">Discount</button></td>
                </tr>
                <tr>
                    <td class="font-mono text-xs">SKU-4012</td>
                    <td>Winter Jacket (L)</td>
                    <td>Fashion</td>
                    <td>Fashion Hub</td>
                    <td class="text-right">65</td>
                    <td class="text-right font-medium">৳ ১,৩০,০০০</td>
                    <td class="text-right">Aug 30, 2024</td>
                    <td class="text-right"><span class="badge badge-danger">139</span></td>
                    <td><button class="text-indigo-600 hover:underline text-sm">Discount</button></td>
                </tr>
                <tr>
                    <td class="font-mono text-xs">SKU-5100</td>
                    <td>Phone Case (Old)</td>
                    <td>Accessories</td>
                    <td>Gadget Palace</td>
                    <td class="text-right">180</td>
                    <td class="text-right font-medium">৳ ৫৪,০০০</td>
                    <td class="text-right">Oct 05, 2024</td>
                    <td class="text-right"><span class="badge badge-warning">103</span></td>
                    <td><button class="text-indigo-600 hover:underline text-sm">Discount</button></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection