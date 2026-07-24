<div class="modal-dialog modal-xl sales-details-modal">
    <div class="modal-content">

        <!-- Modal Header -->
        <div class="modal-header py-3">
            <h5 class="modal-title fw-bold">Online Sell Details</h5>
            <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
        </div>

        <!-- Modal Body -->
        <div class="modal-body pt-3">

            <!-- TOP INFO -->
            <div class="row g-3 mb-4">
                <div class="col-md-4">
                    <div class="p-3 border rounded bg-light h-100">
                        <p class="mb-1"><strong>Invoice No:</strong> {{ $transaction->invoice_no }}</p>
                        
                        <p class="mb-1"><strong>Date:</strong> {{ $transaction->transaction_date }}</p>
                        @if($transaction->delivery_date)
                        <p class="mb-1"><strong>Delivery Date:</strong> {{ $transaction->delivery_date }}</p>
                        @endif
                        
                        @if($transaction->price_expiry_date)
                        <p class="mb-1"><strong>Price Expiry Date:</strong> {{ $transaction->price_expiry_date }}</p>
                        @endif
                        
                        <p class="mb-0"><strong>Shipping Status:</strong> {{ $transaction->shipping_status }}</p>
                       
                    </div>
                </div>

                @if($transaction->contact)
                <div class="col-md-4">
                    <div class="p-3 border rounded h-100">
                        <h6 class="mb-2 text-primary fw-bold">Customer</h6>
                        <p class="mb-1">{{ $transaction->contact->name }}</p>
                        <p class="mb-1">{{ $transaction->contact->mobile }}</p>
                        <p class="mb-0 text-muted">{{ $transaction->contact->address }}</p>
                    </div>
                </div>
                @endif

                @if($transaction->shipping)
                <div class="col-md-4">
                    <div class="p-3 border rounded h-100">
                        <h6 class="mb-2 text-primary fw-bold">Shipping Info</h6>
                        <p class="mb-1">{{ $transaction->shipping->name }}</p>
                        <p class="mb-1">{{ $transaction->shipping->phone }}</p>
                        <p class="mb-0 text-muted">{{ $transaction->shipping->address }}</p>
                    </div>
                </div>
                @endif

                <div class="col-md-4">
                    <div class="p-3 border rounded h-100">
                        <p class="mb-1"><strong>Location:</strong> {{ $transaction->location->name ??'' }}</p>

                        @if($transaction->cancel_request)
                            <span class="badge bg-danger mb-2">Cancel Request</span>
                            <p class="mb-0 small text-muted">{{ $transaction->cancel_note }}</p>
                        @endif
                    </div>
                </div>
            </div>

            <!-- VENDORS -->
            <h5 class="fw-bold mb-3">Vendors & Products</h5>

            <div class="accordion mb-4" id="vendorsAccordion">
                @foreach($transaction->vendor_orders as $i => $order)
                <div class="accordion-item">
                    <h2 class="accordion-header" id="heading{{ $i }}">
                        <button class="accordion-button py-2 px-3 @if($i>0) collapsed @endif"
                                type="button"
                                data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $i }}">
                            <div class="w-100 d-flex flex-wrap align-items-center gap-3">
                                <strong>{{ $order->user->name ?? '' }}</strong>
                                <span class="text-muted">({{ $order->invoice_no }})</span>
                                <span class="badge bg-info">{{ $order->lines->count() }} items</span>

                                <span class="ms-auto small">
                                    <strong>Shipping:</strong> {{ $order->shipping_status }}
                                </span>
                                <span class="small">
                                    <strong>Payment:</strong> {{ $order->payment_status }}
                                </span>
                                <span class="small">
                                    <strong>Charge:</strong> {{ number_format($order->shipping_charge,2) }}
                                </span>
                                <span class="small">
                                    <strong>Discount:</strong> {{ number_format($order->discount_amount,2) }}
                                </span>
                            </div>
                        </button>
                    </h2>

                    <div id="collapse{{ $i }}" class="accordion-collapse collapse @if($i==0) show @endif">
                        <div class="accordion-body p-0">
                            <div class="table-responsive">
                                <table class="table table-sm table-hover align-middle mb-0">
                                    <thead class="table-light">
                                        <tr>
                                            <th>#</th>
                                            <th>Product</th>
                                            <th>SKU</th>
                                            <th class="text-center">Qty</th>
                                            <th class="text-end">Price</th>
                                            <th class="text-end">Total</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @php
                                            $sub_total=0;
                                        @endphp
                                        @foreach($order->lines as $pIndex => $line)
                                        
                                        @php
                                            $sub_total +=$line->price * $line->quantity;
                                        @endphp
                                        
                                        <tr>
                                            <td>{{ $pIndex + 1 }}</td>
                                            <td>
                                                {{ $line->product->name ??'' }}
                                                {{ $line->product && $line->product->type=='variable' ? $line->variation->name : '' }}
                                            </td>
                                            <td>{{ $line->product->sku ??'' }}</td>
                                            <td class="text-center">{{ $line->quantity }}</td>
                                            <td class="text-end">{{ priceFormate($line->price) }}</td>
                                            <td class="text-end">{{ priceFormate($line->price * $line->quantity) }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                    <tfoot class="table-light">
                                        <tr>
                                            <th colspan="5" class="text-end">Vendor Total</th>
                                            <th class="text-end">{{ number_format($order->final_amount,2) }}</th>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- PAYMENT & SUMMARY -->
            <div class="row g-4 mt-4">
                <div class="col-md-8">
                    <div class="border rounded p-3 h-100">
                        <h6 class="fw-bold mb-3">Payment Details</h6>
                        <div class="table-responsive">
                            <table class="table table-bordered table-sm align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>Date</th>
                                        <th>Amount</th>
                                        <th>Method</th>
                                        <th>Note</th>
                                        <th>User</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($transaction->payments as $payment)
                                    <tr>
                                        <td>{{ $payment->paid_on }}</td>
                                        <td>{{ $payment->amount }}</td>
                                        <td>{{ $payment->method }}</td>
                                        <td>{{ $payment->note }}</td>
                                        <td>{{ $payment->user->name??'' }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>

                <div class="col-md-4">
                    <div class="border rounded p-3 bg-light">
                        <h6 class="fw-bold mb-3">Order Summary</h6>
                        <table class="table table-sm mb-0">
                            <tr>
                                <th>Sub Total</th>
                                <td class="text-end">{{ priceFormate($sub_total) }}</td>
                            </tr>
                            <tr>
                                <th>Discount</th>
                                <td class="text-end">{{ priceFormate($transaction->cal_discount) }}</td>
                            </tr>
                            <tr>
                                <th>Shipping</th>
                                <td class="text-end">{{ priceFormate($transaction->shipping_charge) }}</td>
                            </tr>
                            <tr class="table-success">
                                <th>Final Amount</th>
                                <td class="text-end fw-bold">{{ priceFormate($transaction->final_amount) }}</td>
                            </tr>
                            <tr>
                                <th>Paid</th>
                                <td class="text-end">{{ priceFormate($transaction->payments->sum('amount')) }}</td>
                            </tr>
                            <tr class="table-warning">
                                <th>Due</th>
                                <td class="text-end fw-bold">
                                    {{ priceFormate($transaction->final_amount - $transaction->payments->sum('amount')) }}
                                </td>
                            </tr>
                        </table>
                    </div>
                </div>
            </div>

        </div>

        <!-- Modal Footer -->
        <div class="modal-footer">
            <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
        </div>
    </div>
</div>

<!-- OPTIONAL SMALL CSS -->
<style>
.sales-details-modal .table th,
.sales-details-modal .table td {
    padding: 6px 8px;
    font-size: 13px;
}
</style>
