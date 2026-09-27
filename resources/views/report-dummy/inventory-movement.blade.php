@extends('report-dummy.report')
@section('title', 'Inventory Movement')
@section('page-title', 'Inventory Movement Report')
@section('page-subtitle', 'স্টকের ইন/আউট মুভমেন্ট')

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
            <label class="label">Product</label>
            <select class="input"><option>All Products</option></select>
        </div>
        <div>
            <label class="label">Movement Type</label>
            <select class="input">
                <option>All</option>
                <option>Purchase</option>
                <option>Sale</option>
                <option>Return</option>
                <option>Adjustment</option>
                <option>Transfer</option>
            </select>
        </div>
        <div class="flex items-end">
            <button class="btn btn-primary w-full justify-center">🔍 Filter</button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Stock In</p>
        <p class="text-2xl font-bold text-green-600 mt-1">১,২৪০</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Stock Out</p>
        <p class="text-2xl font-bold text-red-600 mt-1">৯৮৫</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Adjustments</p>
        <p class="text-2xl font-bold text-orange-600 mt-1">৩২</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Net Change</p>
        <p class="text-2xl font-bold text-indigo-600 mt-1">+২৫৫</p>
    </div>
</div>

<div class="card">
    <h3 class="font-semibold text-gray-800 mb-4">Movement Log</h3>
    <div class="overflow-x-auto">
        <table class="table-report">
            <thead>
                <tr>
                    <th>Date</th>
                    <th>SKU</th>
                    <th>Product</th>
                    <th>Type</th>
                    <th>Reference</th>
                    <th class="text-right">Qty</th>
                    <th class="text-right">Before</th>
                    <th class="text-right">After</th>
                    <th>By</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Jan 15, 10:24 AM</td>
                    <td class="font-mono text-xs">SKU-1024</td>
                    <td>Wireless Mouse</td>
                    <td><span class="badge badge-success">Purchase</span></td>
                    <td class="font-mono text-xs">PO-2201</td>
                    <td class="text-right font-semibold text-green-600">+50</td>
                    <td class="text-right">12</td>
                    <td class="text-right">62</td>
                    <td>Rahim</td>
                </tr>
                <tr>
                    <td>Jan 15, 11:02 AM</td>
                    <td class="font-mono text-xs">SKU-1024</td>
                    <td>Wireless Mouse</td>
                    <td><span class="badge badge-danger">Sale</span></td>
                    <td class="font-mono text-xs">#ORD-10236</td>
                    <td class="text-right font-semibold text-red-600">-3</td>
                    <td class="text-right">62</td>
                    <td class="text-right">59</td>
                    <td>Karim</td>
                </tr>
                <tr>
                    <td>Jan 15, 02:15 PM</td>
                    <td class="font-mono text-xs">SKU-1087</td>
                    <td>Bluetooth Speaker</td>
                    <td><span class="badge badge-warning">Adjustment</span></td>
                    <td class="font-mono text-xs">ADJ-091</td>
                    <td class="text-right font-semibold text-red-600">-2</td>
                    <td class="text-right">27</td>
                    <td class="text-right">25</td>
                    <td>Manager</td>
                </tr>
                <tr>
                    <td>Jan 15, 03:40 PM</td>
                    <td class="font-mono text-xs">SKU-2011</td>
                    <td>Cotton T-Shirt</td>
                    <td><span class="badge badge-info">Transfer</span></td>
                    <td class="font-mono text-xs">TRF-045</td>
                    <td class="text-right font-semibold text-green-600">+20</td>
                    <td class="text-right">8</td>
                    <td class="text-right">28</td>
                    <td>Warehouse</td>
                </tr>
                <tr>
                    <td>Jan 15, 04:12 PM</td>
                    <td class="font-mono text-xs">SKU-1024</td>
                    <td>Wireless Mouse</td>
                    <td><span class="badge badge-warning">Return</span></td>
                    <td class="font-mono text-xs">RET-012</td>
                    <td class="text-right font-semibold text-green-600">+1</td>
                    <td class="text-right">59</td>
                    <td class="text-right">60</td>
                    <td>Rahim</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection