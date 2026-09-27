@extends('report-dummy.report')
@section('title', 'User Activity')
@section('page-title', 'Cashier / User Activity')
@section('page-subtitle', 'স্টাফদের কার্যক্রম মনিটরিং')

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
            <label class="label">User</label>
            <select class="input"><option>All Users</option></select>
        </div>
        <div class="flex items-end">
            <button class="btn btn-primary w-full justify-center">🔍 Filter</button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Total Sales</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">৳ ৬,৫০,০০০</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Total Discount Given</p>
        <p class="text-2xl font-bold text-orange-600 mt-1">৳ ২৪,৫০০</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Total Voids</p>
        <p class="text-2xl font-bold text-red-600 mt-1">১৮</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Total Refunds</p>
        <p class="text-2xl font-bold text-red-600 mt-1">৬</p>
    </div>
</div>

<div class="card mb-6">
    <h3 class="font-semibold text-gray-800 mb-4">User Scorecard</h3>
    <div class="overflow-x-auto">
        <table class="table-report">
            <thead>
                <tr>
                    <th>User</th>
                    <th>Role</th>
                    <th class="text-right">Orders</th>
                    <th class="text-right">Sales</th>
                    <th class="text-right">AOV</th>
                    <th class="text-right">Discounts</th>
                    <th class="text-right">Voids</th>
                    <th class="text-right">Refunds</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="font-medium">Rahim</td>
                    <td><span class="badge badge-info">Cashier</span></td>
                    <td class="text-right">৮৫০</td>
                    <td class="text-right font-medium">৳ ২,৮০,০০০</td>
                    <td class="text-right">৳ ৩২৯</td>
                    <td class="text-right text-orange-600">৳ ৮,৫০০</td>
                    <td class="text-right">৪</td>
                    <td class="text-right">২</td>
                </tr>
                <tr>
                    <td class="font-medium">Karim</td>
                    <td><span class="badge badge-info">Cashier</span></td>
                    <td class="text-right">৬৪২</td>
                    <td class="text-right font-medium">৳ ২,১০,০০০</td>
                    <td class="text-right">৳ ৩২৭</td>
                    <td class="text-right text-orange-600">৳ ১২,০০০</td>
                    <td class="text-right text-red-600">১০</td>
                    <td class="text-right">৩</td>
                </tr>
                <tr>
                    <td class="font-medium">Jamal</td>
                    <td><span class="badge badge-warning">Manager</span></td>
                    <td class="text-right">৩৫৮</td>
                    <td class="text-right font-medium">৳ ১,৬০,০০০</td>
                    <td class="text-right">৳ ৪৪৭</td>
                    <td class="text-right text-orange-600">৳ ৪,০০০</td>
                    <td class="text-right">৪</td>
                    <td class="text-right">১</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

<div class="card">
    <h3 class="font-semibold text-gray-800 mb-4">Recent Activity Log</h3>
    <div class="overflow-x-auto">
        <table class="table-report">
            <thead>
                <tr>
                    <th>Time</th>
                    <th>User</th>
                    <th>Action</th>
                    <th>Reference</th>
                    <th class="text-right">Amount</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>Jan 31, 04:15 PM</td>
                    <td>Rahim</td>
                    <td><span class="badge badge-success">Sale</span></td>
                    <td class="font-mono text-xs">#ORD-10236</td>
                    <td class="text-right">৳ ৩,৪৫০</td>
                </tr>
                <tr>
                    <td>Jan 31, 03:52 PM</td>
                    <td>Karim</td>
                    <td><span class="badge badge-danger">Void</span></td>
                    <td class="font-mono text-xs">#ORD-10235</td>
                    <td class="text-right text-red-600">- ৳ ৮৯০</td>
                </tr>
                <tr>
                    <td>Jan 31, 03:30 PM</td>
                    <td>Karim</td>
                    <td><span class="badge badge-warning">Discount</span></td>
                    <td class="font-mono text-xs">#ORD-10234</td>
                    <td class="text-right text-orange-600">- ৳ ২০০</td>
                </tr>
                <tr>
                    <td>Jan 31, 02:45 PM</td>
                    <td>Jamal</td>
                    <td><span class="badge badge-info">Price Change</span></td>
                    <td class="font-mono text-xs">SKU-1024</td>
                    <td class="text-right">—</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection