@extends('report-dummy.report')
@section('title', 'Audit Log')
@section('page-title', 'Audit Log')
@section('page-subtitle', 'সিস্টেমে কে কী পরিবর্তন করল')

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
            <label class="label">User</label>
            <select class="input"><option>All</option></select>
        </div>
        <div>
            <label class="label">Action</label>
            <select class="input">
                <option>All</option>
                <option>Create</option>
                <option>Update</option>
                <option>Delete</option>
                <option>Login</option>
            </select>
        </div>
        <div class="flex items-end">
            <button class="btn btn-primary w-full justify-center">🔍 Filter</button>
        </div>
    </div>
</div>

<div class="card">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-gray-800">System Activity Log</h3>
        <input type="text" placeholder="🔍 Search..." class="input max-w-xs">
    </div>
    <div class="overflow-x-auto">
        <table class="table-report">
            <thead>
                <tr>
                    <th>Timestamp</th>
                    <th>User</th>
                    <th>IP Address</th>
                    <th>Action</th>
                    <th>Model</th>
                    <th>Changes</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>2025-01-31 16:15:22</td>
                    <td>rahim@sys</td>
                    <td class="font-mono text-xs">192.168.1.24</td>
                    <td><span class="badge badge-success">CREATE</span></td>
                    <td>Order #10236</td>
                    <td class="text-xs text-gray-600">status: pending → paid</td>
                </tr>
                <tr>
                    <td>2025-01-31 15:52:11</td>
                    <td>karim@sys</td>
                    <td class="font-mono text-xs">192.168.1.31</td>
                    <td><span class="badge badge-danger">DELETE</span></td>
                    <td>Order #10235</td>
                    <td class="text-xs text-gray-600">voided by cashier</td>
                </tr>
                <tr>
                    <td>2025-01-31 15:30:45</td>
                    <td>karim@sys</td>
                    <td class="font-mono text-xs">192.168.1.31</td>
                    <td><span class="badge badge-warning">UPDATE</span></td>
                    <td>Product SKU-1024</td>
                    <td class="text-xs text-gray-600">price: ৳ ৫০০ → ৳ ৪৮০</td>
                </tr>
                <tr>
                    <td>2025-01-31 14:45:18</td>
                    <td>jamal@sys</td>
                    <td class="font-mono text-xs">192.168.1.10</td>
                    <td><span class="badge badge-warning">UPDATE</span></td>
                    <td>Stock SKU-1087</td>
                    <td class="text-xs text-gray-600">qty: 27 → 25 (adjustment)</td>
                </tr>
                <tr>
                    <td>2025-01-31 09:02:00</td>
                    <td>admin@sys</td>
                    <td class="font-mono text-xs">10.0.0.5</td>
                    <td><span class="badge badge-info">LOGIN</span></td>
                    <td>—</td>
                    <td class="text-xs text-gray-600">successful login</td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection