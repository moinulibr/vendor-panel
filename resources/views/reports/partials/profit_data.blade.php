<div class="row g-3 mb-4">
    
    <div class="col-md-2 col-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h6 class="text-muted mb-1">Total Sales</h6>
                <h4 class="fw-bold text-primary">{{$total_sell}}</h4>
            </div>
        </div>
    </div>
    
    <div class="col-md-2 col-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h6 class="text-muted mb-1">Total Sales Return</h6>
                <h4 class="fw-bold text-primary">{{$total_sell_return}}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-2 col-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h6 class="text-muted mb-1">Cost of Goods</h6>
                <h4 class="fw-bold text-danger">{{$total_purchase}}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-2 col-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h6 class="text-muted mb-1">Total Expense</h6>
                <h4 class="fw-bold text-warning">{{$total_expense}}</h4>
            </div>
        </div>
    </div>
    
    <div class="col-md-2 col-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h6 class="text-muted mb-1">Total Income</h6>
                <h4 class="fw-bold text-warning"> {{$total_income}}</h4>
            </div>
        </div>
    </div>

    <div class="col-md-2 col-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <h6 class="text-muted mb-1">Net Profit</h6>
                <h4 class="fw-bold text-success">{{ $net_profit}} </h4>
            </div>
        </div>
    </div>

</div>

<!-- Profit & Loss Table -->
<div class="card border-0 shadow-sm">
    <div class="card-header bg-white fw-bold">
        Profit & Loss Details
    </div>

    <div class="table-responsive p-3">
        <table class="table table-bordered table-sm mb-0 align-middle">
            <tbody>

                <tr class="table-light">
                    <td colspan="2" class="fw-bold">Revenue</td>
                </tr>
                <tr>
                    <td>Product Sales</td>
                    <td class="text-end"> {{$total_sell}}</td>
                </tr>

                <tr class="table-light">
                    <td colspan="2" class="fw-bold">Cost of Goods Sold (COGS)</td>
                </tr>
                <tr>
                    <td>Purchase Cost</td>
                    <td class="text-end"> {{$total_purchase }}</td>
                </tr>


            </tbody>
        </table>
    </div>
</div>