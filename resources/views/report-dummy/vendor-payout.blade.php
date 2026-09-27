@extends('report-dummy.report')

@section('title', 'Vendor Payout Report')
@section('page-title', 'Vendor Payout Report')
@section('page-subtitle', 'ভেন্ডরদের পেমেন্ট সেটেলমেন্ট')

@section('content')

{{-- Filters --}}
<div class="card mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="label">Payout Period</label>
            <input type="month" class="input" value="2025-01">
        </div>
        <div>
            <label class="label">Vendor</label>
            <select class="input">
                <option>All Vendors</option>
                <option>TechZone BD</option>
                <option>Fashion Hub</option>
            </select>
        </div>
        <div>
            <label class="label">Status</label>
            <select class="input">
                <option>All</option>
                <option>Pending</option>
                <option>Paid</option>
                <option>On Hold</option>
            </select>
        </div>
        <div class="flex items-end">
            <button class="btn btn-primary w-full justify-center">🔍 Filter</button>
        </div>
    </div>
</div>

{{-- Summary --}}
<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Total Payable</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">৳ ৮,৪৫,২০০</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Total Commission</p>
        <p class="text-2xl font-bold text-indigo-600 mt-1">৳ ১,২৬,৭৮০</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Pending Vendors</p>
        <p class="text-2xl font-bold text-red-600 mt-1">৭</p>
    </div>
</div>

{{-- Table --}}
<div class="card">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-gray-800">Vendor-wise Breakdown</h3>
        <button class="btn btn-primary">💸 Bulk Payout</button>
    </div>
    <div class="overflow-x-auto">
        <table class="table-report">
            <thead>
                <tr>
                    <th>Vendor</th>
                    <th class="text-right">Gross Sales</th>
                    <th class="text-right">Commission</th>
                    <th class="text-right">Refunds</th>
                    <th class="text-right">Fees</th>
                    <th class="text-right">Net Payable</th>
                    <th>Status</th>
                    <th class="text-right">Action</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>
                        <div class="font-medium">TechZone BD</div>
                        <div class="text-xs text-gray-500">vendor@techzone.com</div>
                    </td>
                    <td class="text-right">৳ ২,৫০,০০০</td>
                    <td class="text-right text-red-600">- ৳ ৩৭,৫০০</td>
                    <td class="text-right text-red-600">- ৳ ৫,২০০</td>
                    <td class="text-right text-red-600">- ৳ ১,০০০</td>
                    <td class="text-right font-semibold">৳ ২,০৬,৩০০</td>
                    <td><span class="badge badge-warning">Pending</span></td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-sm">Pay Now</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="font-medium">Fashion Hub</div>
                        <div class="text-xs text-gray-500">info@fashionhub.com</div>
                    </td>
                    <td class="text-right">৳ ১,৮৫,০০০</td>
                    <td class="text-right text-red-600">- ৳ ২৭,৭৫০</td>
                    <td class="text-right text-red-600">- ৳ ২,১০০</td>
                    <td class="text-right text-red-600">- ৳ ১,০০০</td>
                    <td class="text-right font-semibold">৳ ১,৫৪,১৫০</td>
                    <td><span class="badge badge-success">Paid</span></td>
                    <td class="text-right">
                        <button class="text-gray-500 hover:underline text-sm">View</button>
                    </td>
                </tr>
                <tr>
                    <td>
                        <div class="font-medium">Gadget Palace</div>
                        <div class="text-xs text-gray-500">sales@gadget.com</div>
                    </td>
                    <td class="text-right">৳ ৯৫,০০০</td>
                    <td class="text-right text-red-600">- ৳ ১৪,২৫০</td>
                    <td class="text-right text-red-600">- ৳ ০</td>
                    <td class="text-right text-red-600">- ৳ ১,০০০</td>
                    <td class="text-right font-semibold">৳ ৭৯,৭৫০</td>
                    <td><span class="badge badge-danger">On Hold</span></td>
                    <td class="text-right">
                        <button class="text-indigo-600 hover:underline text-sm">Review</button>
                    </td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="bg-gray-50 font-semibold">
                    <td class="px-4 py-3">Total</td>
                    <td class="px-4 py-3 text-right">৳ ৫,৩০,০০০</td>
                    <td class="px-4 py-3 text-right text-red-600">- ৳ ৭৯,৫০০</td>
                    <td class="px-4 py-3 text-right text-red-600">- ৳ ৭,৩০০</td>
                    <td class="px-4 py-3 text-right text-red-600">- ৳ ৩,০০০</td>
                    <td class="px-4 py-3 text-right">৳ ৪,৪০,২০০</td>
                    <td colspan="2"></td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

@endsection