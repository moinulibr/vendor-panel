@extends('report-dummy.report')
@section('title', 'Ledger Report')
@section('page-title', 'Ledger Report')
@section('page-subtitle', 'হিসাবের বই — লেনদেন ও ব্যালান্স')

@section('content')

{{-- Account Selector Bar --}}
<div class="card mb-6">
    <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
        <div class="md:col-span-2">
            <label class="label">Ledger Account</label>
            <select class="input">
                <option>👤 Rahim Ahmed (Customer)</option>
                <option>🏢 TechZone BD (Vendor)</option>
                <option>💵 Cash Account</option>
                <option>🏦 City Bank - 1234</option>
                <option>📦 Inventory - SKU-1024</option>
            </select>
        </div>
        <div>
            <label class="label">From</label>
            <input type="date" class="input" value="2025-01-01">
        </div>
        <div>
            <label class="label">To</label>
            <input type="date" class="input" value="2025-01-31">
        </div>
        <div class="flex items-end">
            <button class="btn btn-primary w-full justify-center">🔍 Load Ledger</button>
        </div>
    </div>
</div>

{{-- Account Summary Card --}}
<div class="card mb-6 bg-gradient-to-br from-indigo-50 to-white border-indigo-100">
    <div class="flex flex-col md:flex-row md:items-center justify-between gap-4">
        <div class="flex items-center gap-4">
            <div class="w-14 h-14 rounded-full bg-indigo-600 text-white flex items-center justify-center text-2xl font-bold">
                রা
            </div>
            <div>
                <h3 class="text-lg font-bold text-gray-800">Rahim Ahmed</h3>
                <p class="text-sm text-gray-500">rahim@mail.com · +880 1711-000000</p>
                <span class="badge badge-success mt-1">Active Customer</span>
            </div>
        </div>
        <div class="grid grid-cols-3 gap-6 text-right">
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium">Opening</p>
                <p class="text-lg font-bold text-gray-700 mt-1">৳ ০</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium">Total Due</p>
                <p class="text-lg font-bold text-red-600 mt-1">৳ ২,৩৫০</p>
            </div>
            <div>
                <p class="text-xs text-gray-500 uppercase font-medium">Closing</p>
                <p class="text-lg font-bold text-indigo-700 mt-1">৳ ২,৩৫০</p>
            </div>
        </div>
    </div>
</div>

{{-- Mini Stats --}}
<div class="grid grid-cols-1 sm:grid-cols-4 gap-4 mb-6">
    <div class="stat-card border-l-4 border-green-500">
        <p class="text-xs text-gray-500 uppercase font-medium">Total Credit (In)</p>
        <p class="text-2xl font-bold text-green-600 mt-1">৳ ৮৫,০০০</p>
    </div>
    <div class="stat-card border-l-4 border-red-500">
        <p class="text-xs text-gray-500 uppercase font-medium">Total Debit (Out)</p>
        <p class="text-2xl font-bold text-red-600 mt-1">৳ ৮২,৬৫০</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Entries</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">২৪</p>
    </div>
    <div class="stat-card">
        <p class="text-xs text-gray-500 uppercase font-medium">Transactions</p>
        <p class="text-2xl font-bold text-gray-800 mt-1">১৮</p>
    </div>
</div>

{{-- Ledger Table --}}
<div class="card">
    <div class="flex items-center justify-between mb-4">
        <h3 class="font-semibold text-gray-800">Ledger Entries</h3>
        <div class="flex gap-2">
            <button class="btn btn-outline">📧 Email Statement</button>
            <button class="btn btn-outline">🖨️ Print</button>
            <button class="btn btn-primary">⬇️ Export PDF</button>
        </div>
    </div>

    <div class="overflow-x-auto">
        <table class="table-report">
            <thead>
                <tr>
                    <th class="w-32">Date</th>
                    <th>Particulars</th>
                    <th>Reference</th>
                    <th class="text-right w-32">Debit (৳)</th>
                    <th class="text-right w-32">Credit (৳)</th>
                    <th class="text-right w-40">Balance (৳)</th>
                </tr>
            </thead>
            <tbody>
                {{-- Opening Balance --}}
                <tr class="bg-gray-50 font-semibold">
                    <td>Jan 01, 2025</td>
                    <td colspan="2" class="text-gray-600">Opening Balance</td>
                    <td class="text-right">—</td>
                    <td class="text-right">—</td>
                    <td class="text-right text-gray-700">০</td>
                </tr>

                {{-- Entries --}}
                <tr>
                    <td>Jan 03, 2025</td>
                    <td>
                        <div class="font-medium">Product Purchase</div>
                        <div class="text-xs text-gray-500">Wireless Mouse, T-Shirt</div>
                    </td>
                    <td class="font-mono text-xs">#ORD-10101</td>
                    <td class="text-right text-red-600">—</td>
                    <td class="text-right text-green-600">৳ ৩,৪৫০</td>
                    <td class="text-right font-medium">৳ ৩,৪৫০</td>
                </tr>
                <tr>
                    <td>Jan 03, 2025</td>
                    <td>
                        <div class="font-medium">Cash Payment Received</div>
                        <div class="text-xs text-gray-500">Paid at POS counter</div>
                    </td>
                    <td class="font-mono text-xs">#PAY-0451</td>
                    <td class="text-right text-green-600">৳ ৩,৪৫০</td>
                    <td class="text-right text-red-600">—</td>
                    <td class="text-right font-medium">৳ ০</td>
                </tr>
                <tr>
                    <td>Jan 08, 2025</td>
                    <td>
                        <div class="font-medium">Product Purchase (Credit)</div>
                        <div class="text-xs text-gray-500">Bluetooth Speaker x 2</div>
                    </td>
                    <td class="font-mono text-xs">#ORD-10204</td>
                    <td class="text-right text-red-600">—</td>
                    <td class="text-right text-green-600">৳ ৫,২০০</td>
                    <td class="text-right font-medium">৳ ৫,২০০</td>
                </tr>
                <tr>
                    <td>Jan 12, 2025</td>
                    <td>
                        <div class="font-medium">Partial Payment</div>
                        <div class="text-xs text-gray-500">bKash transfer</div>
                    </td>
                    <td class="font-mono text-xs">#PAY-0465</td>
                    <td class="text-right text-green-600">৳ ৩,০০০</td>
                    <td class="text-right text-red-600">—</td>
                    <td class="text-right font-medium">৳ ২,২০০</td>
                </tr>
                <tr>
                    <td>Jan 15, 2025</td>
                    <td>
                        <div class="font-medium">Product Return</div>
                        <div class="text-xs text-gray-500">Damaged Bluetooth Speaker</div>
                    </td>
                    <td class="font-mono text-xs">#RET-0124</td>
                    <td class="text-right text-green-600">৳ ২,৬০০</td>
                    <td class="text-right text-red-600">—</td>
                    <td class="text-right font-medium text-red-600">- ৳ ৪০০</td>
                </tr>
                <tr>
                    <td>Jan 18, 2025</td>
                    <td>
                        <div class="font-medium">Product Purchase</div>
                        <div class="text-xs text-gray-500">Cotton T-Shirt x 4</div>
                    </td>
                    <td class="font-mono text-xs">#ORD-10238</td>
                    <td class="text-right text-red-600">—</td>
                    <td class="text-right text-green-600">৳ ২,০০০</td>
                    <td class="text-right font-medium">৳ ১,৬০০</td>
                </tr>
                <tr>
                    <td>Jan 22, 2025</td>
                    <td>
                        <div class="font-medium">Discount Adjustment</div>
                        <div class="text-xs text-gray-500">Goodwill discount</div>
                    </td>
                    <td class="font-mono text-xs">#ADJ-091</td>
                    <td class="text-right text-green-600">৳ ২৫০</td>
                    <td class="text-right text-red-600">—</td>
                    <td class="text-right font-medium">৳ ১,৩৫০</td>
                </tr>
                <tr>
                    <td>Jan 28, 2025</td>
                    <td>
                        <div class="font-medium">Product Purchase</div>
                        <div class="text-xs text-gray-500">Phone Case, Cable</div>
                    </td>
                    <td class="font-mono text-xs">#ORD-10251</td>
                    <td class="text-right text-red-600">—</td>
                    <td class="text-right text-green-600">৳ ১,০০০</td>
                    <td class="text-right font-medium">৳ ২,৩৫০</td>
                </tr>
            </tbody>
            <tfoot>
                <tr class="bg-gray-100 font-semibold">
                    <td colspan="3" class="px-4 py-3 text-right">Period Total:</td>
                    <td class="px-4 py-3 text-right text-green-700">৳ ৯,৩০০</td>
                    <td class="px-4 py-3 text-right text-red-700">৳ ৬,৯৫০</td>
                    <td class="px-4 py-3 text-right">—</td>
                </tr>
                <tr class="bg-indigo-50 font-bold text-indigo-800">
                    <td colspan="5" class="px-4 py-3 text-right">Closing Balance (Due):</td>
                    <td class="px-4 py-3 text-right">৳ ২,৩৫০</td>
                </tr>
            </tfoot>
        </table>
    </div>

    {{-- Pagination --}}
    <div class="flex items-center justify-between mt-4 text-sm">
        <p class="text-gray-500">Showing 1–8 of 24 entries</p>
        <div class="flex gap-1">
            <button class="px-3 py-1 border rounded-lg hover:bg-gray-50">Prev</button>
            <button class="px-3 py-1 border rounded-lg bg-indigo-600 text-white">1</button>
            <button class="px-3 py-1 border rounded-lg hover:bg-gray-50">2</button>
            <button class="px-3 py-1 border rounded-lg hover:bg-gray-50">3</button>
            <button class="px-3 py-1 border rounded-lg hover:bg-gray-50">Next</button>
        </div>
    </div>
</div>

{{-- Related Actions --}}
<div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-6">
    <div class="card border-l-4 border-green-500">
        <div class="flex items-start gap-3">
            <div class="text-2xl">💰</div>
            <div class="flex-1">
                <p class="font-semibold text-gray-800">Receive Payment</p>
                <p class="text-xs text-gray-500 mt-0.5">এই কাস্টমারের কাছ থেকে টাকা নিন</p>
                <button class="btn btn-primary mt-3 w-full justify-center">Record Payment</button>
            </div>
        </div>
    </div>
    <div class="card border-l-4 border-blue-500">
        <div class="flex items-start gap-3">
            <div class="text-2xl">📄</div>
            <div class="flex-1">
                <p class="font-semibold text-gray-800">Send Statement</p>
                <p class="text-xs text-gray-500 mt-0.5">কাস্টমারকে এই ledger-এর কপি পাঠান</p>
                <button class="btn btn-outline mt-3 w-full justify-center">Send Email</button>
            </div>
        </div>
    </div>
    <div class="card border-l-4 border-orange-500">
        <div class="flex items-start gap-3">
            <div class="text-2xl">⚖️</div>
            <div class="flex-1">
                <p class="font-semibold text-gray-800">Adjustment</p>
                <p class="text-xs text-gray-500 mt-0.5">ডিসকাউন্ট বা correction এন্ট্রি</p>
                <button class="btn btn-outline mt-3 w-full justify-center">Add Adjustment</button>
            </div>
        </div>
    </div>
</div>

@endsection