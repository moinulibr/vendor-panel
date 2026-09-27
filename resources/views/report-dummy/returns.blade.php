@extends('report-dummy.report')
@section('title', 'Return & Refund')
@section('page-title', 'Return & Refund Report')
@section('page-subtitle', 'রিটার্ন ও রিফান্ড বিশ্লেষণ')

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
            <label class="label">Reason</label>
            <select class="input">
                <option>All</option>
                <option>Damaged</option>
                <option>Wrong Item</option>
                <option>Not as Described</option>
                <option>Customer Changed Mind</option>
            </select>
        </div>
        <div class="flex items-end">
            <button class="btn btn-primary w-full justify-center">🔍 Filter</button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Total Returns</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">৬৮</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Return Rate</p>
        <p class="text-2xl font-bold text-orange-600 mt-1">2.4%</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Refund Amount</p>
        <p class="text-2xl font-bold text-red-600 mt-1">৳ ৪৮,২০০</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Avg Resolution</p>
        <p class="text-2xl font-bold text-indigo-600 mt-1">১.৮ দিন</p>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-2 gap-6 mb-6">
    <div class="card">
        <h3 class="font-semibold text-gray-800 mb-4">Return Reasons</h3>
        <div class="space-y-3">
            <div>
                <div class="flex justify-between text-sm mb-1"><span>Damaged</span><span class="font-medium">২৪ (35%)</span></div>
                <div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-red-500 h-2 rounded-full" style="width: 35%"></div></div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1"><span>Wrong Item</span><span class="font-medium">১৮ (26%)</span></div>
                <div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-orange-500 h-2 rounded-full" style="width: 26%"></div></div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1"><span>Not as Described</span><span class="font-medium">১৪ (21%)</span></div>
                <div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-yellow-500 h-2 rounded-full" style="width: 21%"></div></div>
            </div>
            <div>
                <div class="flex justify-between text-sm mb-1"><span>Changed Mind</span><span class="font-medium">১২ (18%)</span></div>
                <div class="w-full bg-gray-100 rounded-full h-2"><div class="bg-blue-500 h-2 rounded-full" style="width: 18%"></div></div>
            </div>
        </div>
    </div>
    <div class="card">
        <h3 class="font-semibold text-gray-800 mb-4">Top Returned Products</h3>
        <div class="space-y-3">
            @for($i = 1; $i <= 4; $i++)
            <div class="flex items-center gap-3">
                <div class="w-8 h-8 rounded-full bg-red-100 text-red-600 flex items-center justify-center font-bold text-sm">{{ $i }}</div>
                <div class="flex-1">
                    <div class="flex justify-between text-sm">
                        <span class="font-medium">Product {{ $i }}</span>
                        <span class="font-semibold">{{ 24 - $i * 3 }} returns</span>
                    </div>
                </div>
            </div>
            @endfor
        </div>
    </div>
</div>

<div class="card">
    <h3 class="font-semibold text-gray-800 mb-4">Return Log</h3>
    <div class="overflow-x-auto">
        <table class="table-report">
            <thead>
                <tr>
                    <th>Return ID</th>
                    <th>Order ID</th>
                    <th>Customer</th>
                    <th>Product</th>
                    <th>Reason</th>
                    <th class="text-right">Refund</th>
                    <th>Vendor</th>
                    <th>Status</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td class="font-mono text-xs">RET-0124</td>
                    <td class="font-mono text-xs">#ORD-10236</td>
                    <td>Rahim Ahmed</td>
                    <td>Wireless Mouse</td>
                    <td>Damaged</td>
                    <td class="text-right font-medium">৳ ৮৫০</td>
                    <td>TechZone BD</td>
                    <td><span class="badge badge-success">Refunded</span></td>
                </tr>
                <tr>
                    <td class="font-mono text-xs">RET-0125</td>
                    <td class="font-mono text-xs">#ORD-10237</td>
                    <td>Sadia Khan</td>
                    <td>Cotton T-Shirt</td>
                    <td>Wrong Size</td>
                    <td class="text-right font-medium">৳ ৫০০</td>
                    <td>Fashion Hub</td>
                    <td><span class="badge badge-warning">Pending</span></td>
                </tr>
                <tr>
                    <td class="font-mono text-xs">RET-0126</td>
                    <td class="font-mono text-xs">#ORD-10240</td>
                    <td>Karim Uddin</td>
                    <td>Bluetooth Speaker</td>
                    <td>Not as Described</td>
                    <td class="text-right font-medium">৳ ১,২০০</td>
                    <td>Gadget Palace</td>
                    <td><span class="badge badge-info">Processing</span></td>
                </tr>
            </tbody>
        </table>
    </div>
</div>

@endsection