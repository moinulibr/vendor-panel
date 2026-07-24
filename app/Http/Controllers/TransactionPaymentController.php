<?php

namespace App\Http\Controllers;

use App\Models\TransactionPayment;
use App\Models\ContactNextPayment;
use App\Models\Transaction;
use App\Models\Contact;
use Illuminate\Http\Request;
use App\Utils\ProductUtil;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;


class TransactionPaymentController extends Controller
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

        return view('transactions.payment_invoice', compact('item'));
    }

    public function edit(string $id)
    {
        $item = Transaction::find($id);
        $due = ($item->final_amount ?? 0) - $item->payments->sum('amount');
        $methods = $this->productUtil->paymentTypes();

        return view('transactions.payment_form', compact('item', 'due', 'methods'));
    }

    public function update(Request $request, string $id)
    {
        $transaction = Transaction::with('payments')->findOrFail($id);

        $data = $request->validate([
            'method' => ['required', 'string', 'max:50'],
            'amount' => ['required', 'numeric', 'min:0.01'],
            'note' => ['nullable', 'string'],
            'paid_on' => ['nullable', 'date'],
            'transaction_no' => ['nullable'],
            'provider' => ['nullable'],
            'account_no' => ['nullable'],
            'bank_name' => ['nullable'],
            'card_title' => ['nullable'],
            'card_number' => ['nullable'],
            'account_id' => ['nullable', 'integer', 'exists:accounts,id'],
            'next_payment_date' => ['nullable', 'date'],
        ]);
        
        $next_payment_date=$data['next_payment_date'];
        $dueAmount = max(0, (float) ($transaction->final_amount ?? 0) - (float) $transaction->payments->sum('amount'));

        if ($dueAmount <= 0) {
            return response()->json([
                'status' => false,
                'msg' => 'This transaction has no outstanding balance.',
            ], 422);
        }

        $amountToApply = min($data['amount'], $dueAmount);
        
        $data['user_id']=auth()->id();
        $data['paid_on']=isset($data['paid_on']) ? Carbon::parse($data['paid_on']) : now();
        $data['transaction_id']=$transaction->id;
        TransactionPayment::create($data);
        
        if(!empty($next_payment_date)){
            ContactNextPayment::create([
                'next_payment_date'=>$next_payment_date,
                'contact_id'=>$id,
                'current_date'=>$data['paid_on'],
                'current_note'=>$data['note'],
                'current_reveived_amount'=>$data['amount'],
            
            ]);
        }
        

        $this->productUtil->transactionStatus($transaction->fresh('payments'));

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

    public function payDueStore(Request $request, $id)
    {
        $contact=Contact::find($id);
        $data = $request->validate([
            'method' => 'required|string',
            'amount' => 'required|numeric|min:0.01',
            'note' => 'nullable|string',
            'paid_on' => 'nullable|date',
            'next_payment_date' => 'nullable|date',
            'transaction_no' => 'nullable|string',
            'account_id' => 'nullable|integer',
        ]);

        $contactType = $contact->type;
        $remaining = (float) $data['amount'];
        $applied = 0.0;
        $paidOnInput = $data['paid_on'] ?? null;
        $paidOn = $paidOnInput ? Carbon::parse($paidOnInput) : now();
        $method = $data['method'];
        $note = $data['note'] ?? null;
        $accountId = $data['account_id'] ?? null;
        $userId = auth()->id();

        $transactionTypes = $contactType === 'supplier'
            ? ['purchase', 'opening_balance']
            : ['sell', 'opening_balance'];

        DB::transaction(function () use (&$remaining, &$applied, $data, $transactionTypes, $paidOn, $method, $note, $accountId, $userId,$id) {
            $transactions = Transaction::query()
                ->with('payments')
                ->where('contact_id', $id)
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
                    'is_due' => 1,
                ]);

                $this->productUtil->transactionStatus($transaction->fresh());

                $applied += $payPortion;
                $remaining -= $payPortion;
            }
        });
        
        $next_payment_date=$data['next_payment_date'];
        if(!empty($next_payment_date)){
            ContactNextPayment::create([
                'next_payment_date'=>$next_payment_date,
                'contact_id'=>$id,
                'current_date'=>$data['paid_on'],
                'current_note'=>$data['note'],
                'current_reveived_amount'=> $remaining,
            
            ]);
        }
        

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
