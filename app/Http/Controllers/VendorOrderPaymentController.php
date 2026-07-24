<?php

namespace App\Http\Controllers;

use App\Models\TransactionPayment;
use App\Models\Transaction;
use App\Models\VendorOrder;
use Illuminate\Http\Request;
use App\Utils\ProductUtil;
use Carbon\Carbon;

class VendorOrderPaymentController extends Controller
{
    public function __construct(ProductUtil $productUtil){
        
        $this->productUtil=$productUtil;
        
    }
    
    
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    public function show(string $id)
    {
        $item = TransactionPayment::find($id);

        return view('vendor_orders.payment_invoice', compact('item'));
    }

    public function edit(string $id)
    {
        $item = VendorOrder::find($id);
        $due = ($item->final_amount ?? 0) - $item->payments->sum('amount');
        $methods = $this->productUtil->paymentTypes();

        return view('vendor_orders.payment_form', compact('item', 'due', 'methods'));
    }

    public function update(Request $request, string $id)
    {
        $vebdor_order = VendorOrder::with('payments')->findOrFail($id);
        $transaction=Transaction::with('payments')->find($vebdor_order->transaction_id);
        
        $data = $request->validate([
            'method' => ['required', 'string', 'max:50'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'note' => ['nullable', 'string'],
            'paid_on' => ['nullable', 'date'],
            'account_id' => ['nullable', 'integer', 'exists:accounts,id'],
        ]);

        $dueAmount = max(0, (float) ($vebdor_order->final_amount ?? 0) - (float) $vebdor_order->payments->sum('amount'));

        if ($dueAmount <= 0) {
            return response()->json([
                'status' => false,
                'msg' => 'This transaction has no outstanding balance.',
            ], 422);
        }

        $amountToApply = min($data['amount'], $dueAmount);

        TransactionPayment::create([
            'transaction_id' => $transaction->id,
            'vendor_order_id' => $vebdor_order->id,
            'method' => $data['method'],
            'amount' => $amountToApply,
            'note' => $data['note'] ?? null,
            'paid_on' => isset($data['paid_on']) ? Carbon::parse($data['paid_on']) : now(),
            'account_id' => $data['account_id'] ?? null,
            'user_id' => auth()->id(),
        ]);

        $this->productUtil->transactionStatus($vebdor_order);
        $this->productUtil->transactionStatus($transaction);

        $message = 'Payment recorded successfully.';

        if ($data['amount'] > $dueAmount) {
            $message .= ' Excess amount not applied.';
        }

        return response()->json([
            'status' => true,
            'msg' => $message,
            'function' => 'refresh',
        ]);
    }

    public function payDue(Request $request)
    {
        $data = $request->validate([
            'payment_method' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'note' => 'nullable|string',
            'paid_on' => 'nullable|date',
            'transaction_no' => 'nullable|string',
            'account_id' => 'nullable|integer',
            'contact_id' => 'required|integer',
            'contact_type' => 'nullable|string|in:customer,supplier',
        ]);

        $contactType = $data['contact_type'] ?? 'customer';
        $contactModel = $contactType === 'supplier' ? Supplier::class : Customer::class;

        if (! $contactModel::whereKey($data['contact_id'])->exists()) {
            return response()->json([
                'status' => false,
                'msg' => 'Selected contact was not found.',
            ], 422);
        }

        $remaining = (float) $data['amount'];
        $applied = 0.0;
        $paidOnInput = $data['paid_on'] ?? null;
        $paidOn = $paidOnInput ? Carbon::parse($paidOnInput) : now();
        $method = $data['payment_method'];
        $note = $data['note'] ?? null;
        $accountId = $data['account_id'] ?? null;
        $userId = auth()->id();

        $transactionTypes = $contactType === 'supplier'
            ? ['purchase', 'opening_balance']
            : ['sell', 'opening_balance'];

        DB::transaction(function () use (&$remaining, &$applied, $data, $transactionTypes, $paidOn, $method, $note, $accountId, $userId) {
            $transactions = Transaction::query()
                ->with('payments')
                ->where('contact_id', $data['contact_id'])
                ->whereIn('type', $transactionTypes)
                ->orderBy('transaction_date')
                ->orderBy('id')
                ->lockForUpdate()
                ->get();

            foreach ($transactions as $transaction) {
                if ($remaining <= 0) {
                    break;
                }

                $dueAmount = (float) ($transaction->final_amount ?? 0) - (float) $transaction->payments->sum('amount');

                if ($dueAmount <= 0) {
                    continue;
                }

                $payPortion = min($dueAmount, $remaining);

                TransactionPayment::create([
                    'transaction_id' => $transaction->id,
                    'method' => $method,
                    'user_id' => $userId,
                    'account_id' => $accountId,
                    'amount' => $payPortion,
                    'note' => $note,
                    'paid_on' => $paidOn,
                ]);

                $this->productUtil->transactionStatus($transaction->fresh());

                $applied += $payPortion;
                $remaining -= $payPortion;
            }
        });

        if ($applied <= 0) {
            $failureMessage = $contactType === 'supplier'
                ? 'No outstanding payables found for this supplier.'
                : 'No outstanding dues found for this customer.';

            return response()->json([
                'status' => false,
                'msg' => $failureMessage,
            ]);
        }

        $message = 'Payment recorded successfully.';
        if ($remaining > 0) {
            $message .= ' Unapplied amount: ' . formatAmount($remaining) . '.';
        }

        return response()->json([
            'status' => true,
            'msg' => $message,
            'function' => 'getData',
        ]);
    }
    
    
}
