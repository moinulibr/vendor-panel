@extends('report-dummy.report')

@section('title', 'Low Stock Alert')
@section('page-title', 'Low Stock Alert')
@section('page-subtitle', 'যেসব প্রোডাক্ট রিস্টক দরকার')

@section('content')

{{-- Alert Banner --}}
<div class="bg-red-50 border border-red-200 rounded-xl p-4 mb-6 flex items-start gap-3">
    <div class="text-2xl">⚠️</div>
    <div>
        <h3 class="font-semibold text-red-800">২৪টি প্রোডাক্ট লো স্টকে</h3>
        <p class="text-sm text-red-700 mt-0.5">এর মধ্যে ৬টি সম্পূর্ণ শেষ। এখনই রিস্টক করুন।</p>
    </div>
</div>

{{-- Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="stat-card border-l-4 border-red-500">
        <p class="text-xs text-gray-500 uppercase font-medium">Out of Stock</p>
        <p class="text-2xl font-bold text-red-600 mt-1">৬</p>
    </div>
    <div class="stat-card border-l-4 border-yellow-500">
        <p class="text-xs text-gray-500 uppercase font-medium">Low Stock</p>
        <p class="text-2xl font-bold text-yellow-600 mt-1">১৮</p>
    </div>
    <div class="stat-card border-l-4 border-blue-500">
        <p class="text-xs text-gray-500 uppercase font-medium">Stock Value at Risk</p>
        <p class="text-2xl font-bold text-blue-600 mt-1">৳ ২,৪৫,০০০</p>
    </div>
</div>

{{-- Filters --}}
<div class="card mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="label">Category</label>
            <select class="input">
                <option>All Categories</option>
                <option>Electronics</option>
                <option>Fashion</option>
            </select>
        </div>
        <div>
            <label class="label">Vendor</label>
            <select class="input">
                <option>All Vendors</option>
            </select>
        </div>
        <div>
            <label class="label">Warehouse</label>
            <select class="input">
                <option>All</option>
                <option>Main Warehouse</option>
            </select>
        </div>
        <div class="flex items-end">
            <button class="btn btn-primary w-full justify-center">🔍 Filter</button>
        </div>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-gray-800">Low Stock Items</h3>
        <div class="flex gap-2">
            <button class="btn btn-outline">📧 Email Alert</button>
            <button class="btn btn-primary">🛒 Create Purchase Order</button>
        </div>
    </div>
    <div class="overflow-x-auto">
        <table class="table-report">
            <thead>
                <tr>
                    <th><input type="checkbox"></th>
                    <th>SKU</th>
                    <th>Product</th>
                    <th>Category</th>
                    <th>Vendor</th>
                    <th class="text-right">Current</th>
                    <th class="text-right">Reorder Level</th>
                    <th class="text-right">Need</th>
                    <th>Status</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td><input type="checkbox"></td>
                    <td class="font-mono text-xs">SKU-1024</td>
                    <td>
                        <div class="font-medium">Wireless Mouse</div>
                        <div class="text-xs text-gray-500">Logitech M235</div>
                    </td>
                    <td>Electronics</td>
                    <td>TechZone BD</td>
                    <td class="text-right font-semibold text-red-600">0</td>
                    <td class="text-right">20</td>
                    <td class="text-right font-medium">20</td>
                    <td><span class="badge badge-danger">Out of Stock</span></td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-sm">Restock</button>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox"></td>
                    <td class="font-mono text-xs">SKU-1087</td>
                    <td>
                        <div class="font-medium">Bluetooth Speaker</div>
                        <div class="text-xs text-gray-500">JBL Go 3</div>
                    </td>
                    <td>Electronics</td>
                    <td>Gadget Palace</td>
                    <td class="text-right font-semibold text-yellow-600">5</td>
                    <td class="text-right">25</td>
                    <td class="text-right font-medium">20</td>
                    <td><span class="badge badge-warning">Low</span></td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-sm">Restock</button>
                    </td>
                </tr>
                <tr>
                    <td><input type="checkbox"></td>
                    <td class="font-mono text-xs">SKU-2011</td>
                    <td>
                        <div class="font-medium">Cotton T-Shirt</div>
                        <div class="text-xs text-gray-500">Size M - Black</div>
                    </td>
                    <td>Fashion</td>
                    <td>Fashion Hub</td>
                    <td class="text-right font-semibold text-yellow-600">8</td>
                    <td class="text-right">40</td>
                    <td class="text-right font-medium">32</td>
                    <td><span class="badge badge-warning">Low</span></td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-sm">Restock</button>
                    </td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection