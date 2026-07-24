<div class="rable-responsive">

    
    <table class="table table-bordered table-striped">
        <thead class="table-dark">
            <tr>
                <th>#</th>
                <th>Month</th>
                <th>Total Sale</th>
                <th>Total Purchase</th>
                <th>Total Expense</th>
                <th>Total Income</th>
            </tr>
        </thead>
        <tbody>
            @foreach($items as $index => $item)
            <tr>
                <td>{{ $index + 1 }}</td>
                <td>{{ date('F', mktime(0, 0, 0, $item->month, 1)) }} {{ $item->year }}</td>
                
                <td>{{ priceFormate($item->total_sell_amount) }}</td>
                <td>{{ priceFormate($item->total_purchase_amount) }}</td>
                <td>{{ priceFormate($item->total_expense_amount) }}</td>
                <td>{{ priceFormate($item->total_income_amount) }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    
</div>