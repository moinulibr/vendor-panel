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
    {{-- Sidebar --}}
    <aside class="w-64 bg-white border-r border-gray-200 hidden md:block">
        <div class="p-5 border-b border-gray-100">
            <h1 class="text-lg font-bold text-indigo-600">📊 ReportHub</h1>
        </div>
        <nav class="p-3 space-y-1 text-sm">
            <a href="#" class="block px-3 py-2 rounded-lg hover:bg-indigo-50 hover:text-indigo-600">Dashboard</a>
            <a href="#" class="block px-3 py-2 rounded-lg bg-indigo-50 text-indigo-600 font-medium">Daily Sales</a>
            <a href="#" class="block px-3 py-2 rounded-lg hover:bg-indigo-50 hover:text-indigo-600">Vendor Payout</a>
            <a href="#" class="block px-3 py-2 rounded-lg hover:bg-indigo-50 hover:text-indigo-600">Low Stock</a>
            <a href="#" class="block px-3 py-2 rounded-lg hover:bg-indigo-50 hover:text-indigo-600">Profit & Loss</a>
            <a href="#" class="block px-3 py-2 rounded-lg hover:bg-indigo-50 hover:text-indigo-600">Inventory</a>
            <a href="#" class="block px-3 py-2 rounded-lg hover:bg-indigo-50 hover:text-indigo-600">Customers</a>
        </nav>
    </aside>

    {{-- Main --}}
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