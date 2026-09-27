@extends('report-dummy.report')
@section('title', 'Tax / VAT Report')
@section('page-title', 'Tax / VAT Report')
@section('page-subtitle', 'ভ্যাট ও ট্যাক্স সারসংক্ষেপ')

@section('content')

<div class="card mb-6">
    <div class="grid grid-cols-1 md:grid-cols-4 gap-4">
        <div>
            <label class="label">Period</label>
            <input type="month" class="input" value="2025-01">
        </div>
        <div>
            <label class="label">Tax Type</label>
            <select class="input">
                <option>VAT</option>
                <option>Income Tax</option>
                <option>AIT</option>
            </select>
        </div>
        <div>
            <label class="label">Rate</label>
            <select class="input">
                <option>All</option>
                <option>5%</option>
                <option>7.5%</option>
                <option>15%</option>
            </select>
        </div>
        <div class="flex items-end">
            <button class="btn btn-primary w-full justify-center">🔍 Generate</button>
        </div>
    </div>
</div>

<div class="grid grid-cols-1 sm:grid-cols-3 gap-4 mb-6">
    <div class="stat-card border-l-4 border-blue-500">
        <p class="text-xs text-gray-500 uppercase font-medium">Output VAT (Sales)</p>
        <p class="text-2xl font-bold text-blue-600 mt-1">৳ ১,৮৭,৫০০</p>
    </div>
    <div class="stat-card border-l-4 border-green-500">
        <p class="text-xs text-gray-500 uppercase font-medium">Input VAT (Purchase)</p>
        <p class="text-2xl font-bold text-green-600 mt-1">৳ ১,১০,০০০</p>
    </div>
    <div class="stat-card border-l-4 border-red-500">
        <p class="text-xs text-gray-500 uppercase font-medium">Net VAT Payable</p>
        <p class="text-2xl font-bold text-red-600 mt-1">৳ ৭৭,৫০০</p>
    </div>
</div>

<div class="card mb-6">
    <h3 class="font-semibold text-gray-800 mb-4">VAT Summary</h3>
    <div class="overflow-x-auto">
        <table class="table-report">
            <thead>
                <tr>
                    <th>Rate</th>
                    <th class="text-right">Taxable Sales</th>
                    <th class="text-right">Output VAT</th>
                    <th class="text-right">Taxable Purchase</th>
                    <th class="text-right">Input VAT</th>
                    <th class="text-right">Net Payable</th>
                </tr>
            </thead>
            <tbody>
                <tr>
                    <td>5%</td>
                    <td class="text-right">৳ ৫,০০,০০০</td>
                    <td class="text-right">৳ ২৫,০০০</td>
                    <td class="text-right">৳ ২,০০,০০০</td>
                    <td class="text-right">৳ ১০,০০০</td>
                    <td class="text-right font-medium">৳ ১৫,০০০</td>
                </tr>
                <tr>
                    <td>7.5%</td>
                    <td class="text-right">৳ ৮,০০,০০০</td>
                    <td class="text-right">৳ ৬০,০০০</td>
                    <td class="text-right">৳ ৫,০০,০০০</td>
                    <td class="text-right">৳ ৩৭,৫০০</td>
                    <td class="text-right font-medium">৳ ২২,৫০০</td>
                </tr>
                <tr>
                    <td>15%</td>
                    <td class="text-right">৳ ৬,৮৩,৩৩৩</td>
                    <td class="text-right">৳ ১,০২,৫০০</td>
                    <td class="text-right">৳ ৪,১৬,৬৬৭</td>
                    <td class="text-right">৳ ৬২,৫০০</td>
                    <td class="text-right font-medium">৳ ৪০,০০০</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="bg-gray-50 font-semibold">
                    <td class="px-4 py-3">Total</td>
                    <td class="px-4 py-3 text-right">৳ ১৯,৮৩,৩৩৩</td>
                    <td class="px-4 py-3 text-right">৳ ১,৮৭,৫০০</td>
                    <td class="px-4 py-3 text-right">৳ ১১,১৬,৬৬৭</td>
                    <td class="px-4 py-3 text-right">৳ ১,১০,০০০</td>
                    <td class="px-4 py-3 text-right text-red-600">৳ ৭৭,৫০০</td>
                </tr>
            </tfoot>
        </table>
    </div>
</div>

<div class="bg-blue-50 border border-blue-200 rounded-xl p-4">
    <div class="flex justify-between items-center">
        <div>
            <h3 class="font-semibold text-blue-800">📄 NBR Return Submission</h3>
            <p class="text-sm text-blue-700 mt-0.5">মাসিক ভ্যাট রিটার্ন জমা দেওয়ার শেষ তারিখ: ১৫ ফেব্রুয়ারি, ২০২৫</p>
        </div>
        <button class="btn btn-primary">⬇️ Download Mushak 9.1</button>
    </div>
</div>

@endsection