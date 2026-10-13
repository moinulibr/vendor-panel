<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Report') - {{ config('app.name') }}</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
        .card { @apply bg-white rounded-xl shadow-sm border border-gray-100 p-5; }
        .stat-card { @apply bg-white rounded-xl shadow-sm border border-gray-100 p-5 hover:shadow-md transition; }
        .btn { @apply inline-flex items-center gap-2 px-4 py-2 rounded-lg text-sm font-medium transition; }
        .btn-primary { @apply bg-indigo-600 text-white hover:bg-indigo-700; }
        .btn-outline { @apply border border-gray-300 text-gray-700 hover:bg-gray-50; }
        .input { @apply w-full px-3 py-2 border border-gray-300 rounded-lg text-sm focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 outline-none; }
        .label { @apply block text-xs font-medium text-gray-600 mb-1; }
        .table-report { @apply w-full text-sm; }
        .table-report thead { @apply bg-gray-50 text-gray-600 text-xs uppercase tracking-wider; }
        .table-report th { @apply px-4 py-3 text-left font-semibold; }
        .table-report td { @apply px-4 py-3 border-t border-gray-100 text-gray-700; }
        .table-report tbody tr:hover { @apply bg-gray-50; }
        .badge { @apply inline-flex px-2 py-1 text-xs font-medium rounded-full; }
        .badge-success { @apply bg-green-100 text-green-700; }
        .badge-warning { @apply bg-yellow-100 text-yellow-700; }
        .badge-danger  { @apply bg-red-100 text-red-700; }
        .badge-info    { @apply bg-blue-100 text-blue-700; }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">

<div class="flex min-h-screen">
    {{-- Sidebar / Navbar --}}
    <aside class="w-64 bg-white border-r border-gray-200 hidden md:block">
        <div class="p-5 border-b border-gray-100">
            <h1 class="text-lg font-bold text-indigo-600">
                <a href="{{ route('reports.dashboard') }}">📊 ReportHub</a>
            </h1>
        </div>
        
        <nav class="p-3 space-y-1 text-sm overflow-y-auto max-h-[calc(100vh-80px)]">
            
            <a href="{{ route('reports.dashboard') }}" 
               class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('reports.dashboard') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'hover:bg-indigo-50 hover:text-indigo-600 text-gray-600' }}">
                Dashboard
            </a>
            
            <a href="{{ route('reports.daily-sales') }}" 
               class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('reports.daily-sales') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'hover:bg-indigo-50 hover:text-indigo-600 text-gray-600' }}">
                Daily Sales
            </a>

            <a href="{{ route('reports.sales-by-product') }}" 
               class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('reports.sales-by-product') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'hover:bg-indigo-50 hover:text-indigo-600 text-gray-600' }}">
                Sales By Product
            </a>

            <a href="{{ route('reports.sales-by-channel') }}" 
               class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('reports.sales-by-channel') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'hover:bg-indigo-50 hover:text-indigo-600 text-gray-600' }}">
                Sales By Channel
            </a>
            
            <a href="{{ route('reports.vendor-payout') }}" 
               class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('reports.vendor-payout') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'hover:bg-indigo-50 hover:text-indigo-600 text-gray-600' }}">
                Vendor Payout
            </a>

            <a href="{{ route('reports.vendor-performance') }}" 
               class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('reports.vendor-performance') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'hover:bg-indigo-50 hover:text-indigo-600 text-gray-600' }}">
                Vendor Performance
            </a>

            <a href="{{ route('reports.commission') }}" 
               class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('reports.commission') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'hover:bg-indigo-50 hover:text-indigo-600 text-gray-600' }}">
                Commission
            </a>

            <a href="{{ route('reports.inventory-movement') }}" 
               class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('reports.inventory-movement') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'hover:bg-indigo-50 hover:text-indigo-600 text-gray-600' }}">
                Inventory Movement
            </a>
            
            <a href="{{ route('reports.low-stock') }}" 
               class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('reports.low-stock') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'hover:bg-indigo-50 hover:text-indigo-600 text-gray-600' }}">
                Low Stock
            </a>

            <a href="{{ route('reports.dead-stock') }}" 
               class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('reports.dead-stock') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'hover:bg-indigo-50 hover:text-indigo-600 text-gray-600' }}">
                Dead Stock
            </a>

            <a href="{{ route('reports.stock-valuation') }}" 
               class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('reports.stock-valuation') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'hover:bg-indigo-50 hover:text-indigo-600 text-gray-600' }}">
                Stock Valuation
            </a>
            
            <a href="{{ route('reports.profit-loss') }}" 
               class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('reports.profit-loss') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'hover:bg-indigo-50 hover:text-indigo-600 text-gray-600' }}">
                Profit & Loss
            </a>
            
            <a href="{{ route('reports.customers') }}" 
               class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('reports.customers') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'hover:bg-indigo-50 hover:text-indigo-600 text-gray-600' }}">
                Customers
            </a>

            <a href="{{ route('reports.returns') }}" 
               class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('reports.returns') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'hover:bg-indigo-50 hover:text-indigo-600 text-gray-600' }}">
                Returns
            </a>

            <a href="{{ route('reports.expenses') }}" 
               class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('reports.expenses') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'hover:bg-indigo-50 hover:text-indigo-600 text-gray-600' }}">
                Expenses
            </a>

            <a href="{{ route('reports.tax') }}" 
               class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('reports.tax') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'hover:bg-indigo-50 hover:text-indigo-600 text-gray-600' }}">
                Tax
            </a>

            <a href="{{ route('reports.user-activity') }}" 
               class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('reports.user-activity') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'hover:bg-indigo-50 hover:text-indigo-600 text-gray-600' }}">
                User Activity
            </a>

            <a href="{{ route('reports.audit-log') }}" 
               class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('reports.audit-log') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'hover:bg-indigo-50 hover:text-indigo-600 text-gray-600' }}">
                Audit Log
            </a>

            <a href="{{ route('reports.ledger') }}" 
               class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('reports.ledger') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'hover:bg-indigo-50 hover:text-indigo-600 text-gray-600' }}">
                General Ledger
            </a>

            <a href="{{ route('reports.customer-due-aging') }}" 
               class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('reports.customer-due-aging') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'hover:bg-indigo-50 hover:text-indigo-600 text-gray-600' }}">
                Customer Due Aging
            </a>

            <a href="{{ route('reports.purchase-orders') }}" 
               class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('reports.purchase-orders') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'hover:bg-indigo-50 hover:text-indigo-600 text-gray-600' }}">
                Purchase Order
            </a>

            <a href="{{ route('reports.cash-register') }}" 
               class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('reports.cash-register') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'hover:bg-indigo-50 hover:text-indigo-600 text-gray-600' }}">
                Cash Register
            </a>

            <a href="{{ route('reports.journal-entries') }}" 
               class="block px-3 py-2 rounded-lg transition {{ request()->routeIs('reports.journal-entries') ? 'bg-indigo-50 text-indigo-600 font-medium' : 'hover:bg-indigo-50 hover:text-indigo-600 text-gray-600' }}">
                Journal Entries
            </a>
        </nav>
    </aside>

    {{-- Main Content Section --}}
    <main class="flex-1">
        {{-- Topbar --}}
        <header class="bg-white border-b border-gray-200 px-6 py-4 flex items-center justify-between">
            <div>
                <h2 class="text-xl font-semibold text-gray-800">@yield('page-title', 'Report')</h2>
                <p class="text-xs text-gray-500 mt-0.5">@yield('page-subtitle', '')</p>
            </div>
            <div class="flex items-center gap-3">
                <button class="btn btn-outline">🖨️ Print</button>
                <button class="btn btn-primary">⬇️ Export</button>
            </div>
        </header>

        <div class="p-6">
            @yield('content')
        </div>
    </main>
</div>

@stack('scripts')
</body>
</html>