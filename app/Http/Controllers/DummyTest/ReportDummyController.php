<?php

namespace App\Http\Controllers\DummyTest;

use App\Http\Controllers\Controller;

class ReportDummyController extends Controller
{
    public function dashboard()
    {
        return view('report-dummy.dashboard');
    }
    public function dailySales()
    {
        return view('report-dummy.daily-sales');
    }
    public function salesByProduct()
    {
        return view('report-dummy.sales-by-product');
    }
    public function salesByChannel()
    {
        return view('report-dummy.sales-by-channel');
    }
    public function vendorPayout()
    {
        return view('report-dummy.vendor-payout');
    }
    public function vendorPerformance()
    {
        return view('report-dummy.vendor-performance');
    }
    public function commission()
    {
        return view('report-dummy.commission');
    }
    public function inventoryMovement()
    {
        return view('report-dummy.inventory-movement');
    }
    public function lowStock()
    {
        return view('report-dummy.low-stock');
    }
    public function deadStock()
    {
        return view('report-dummy.dead-stock');
    }
    public function stockValuation()
    {
        return view('report-dummy.stock-valuation');
    }
    public function customers()
    {
        return view('report-dummy.customers');
    }
    public function returns()
    {
        return view('report-dummy.returns');
    }
    public function expenses()
    {
        return view('report-dummy.expenses');
    }
    public function tax()
    {
        return view('report-dummy.tax');
    }
    public function profitLoss()
    {
        return view('report-dummy.profit-loss');
    }
    public function userActivity()
    {
        return view('report-dummy.user-activity');
    }
    public function auditLog()
    {
        return view('report-dummy.audit-log');
    }
     public function ledger()
    {
        return view('report-dummy.ledger');
    }
}
