<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Transaction;

use App\Utils\ProductUtil;
use App\Utils\TransactionUtil;
use Illuminate\Support\Facades\DB;

class SellReturnController extends Controller
{
    public $productUtil;
    public $transactionUtil;
    function __construct(ProductUtil $productUtil, TransactionUtil $transactionUtil)
    {
        // $this->middleware('permission:products.view|products.create|products.edit|products.delete', ['only' => ['index','show']]);
        // $this->middleware('permission:products.create', ['only' => ['create','store']]);
        // $this->middleware('permission:products.edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:products.delete', ['only' => ['destroy']]);
        $this->productUtil=$productUtil;
        $this->transactionUtil=$transactionUtil;
    }
    
    public function index(Request $request)
    {
        
        if ($request->ajax()) {
            $search=$request->q;
            $payment_status=$request->payment_status;
            $date=$request->date;
            $contact_id=$request->contact_id;
            
            $query = Transaction::latest()->where(['is_new'=>0,'type'=>'sell_return']);
                if($search){
                    $query->where(function($row) use($search){
                        $row->where('transactions.invoice_no', 'LIKE', '%'. $search. '%');
                        $row->orwhere('transactions.note', 'LIKE', '%'. $search. '%');
                    });
                }
            if($contact_id){
                $query->where('transactions.contact_id', $contact_id);
            }

            
            if($payment_status){
                $query->where('payment_status', $payment_status);
            }
            
            if($date){
                
                $dates  = explode('~', $date);
                
                $start = isset($dates[0]) ? trim($dates[0]) : null;
                $end   = isset($dates[1]) ? trim($dates[1]) : null;
                $query->whereDate('transaction_date', '>=', $start)->whereDate('transaction_date', '<=', $end);
                
            }
            
            
            $items=$query->paginate(30);

            return view('sell_returns.data',compact('items'))->render();
        }
        
        $contacts = Contact::where(['is_new'=>0,'type'=>'customer'])->get();
        return view('sell_returns.index', compact('contacts'));
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $sell = Transaction::find($id);

        foreach ($sell->lines as $key => $value) {

            $sell->lines[$key]->formatted_qty = $value->quantity;
        }
        
        return view('sell_returns.create', compact('sell'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $data=$request->validate([
             'note'=> '',
             'final_amount'=> 'required|numeric'
        ]);
        
        
        try {
            $input = $request->except('_token');

            if (!empty($input['products'])) {
        
                $user_id = auth()->user()->id;

                
                $sell = Transaction::with(['lines'])
                                ->findOrFail($id);

                //Check if any sell return exists for the sale
                $sell_return = Transaction::where('type', 'sell_return')
                        ->where('return_parent_id', $sell->id)
                        ->first();

                $sell_return_data = [
                    'final_amount' => $data['final_amount']
                ];
 
                DB::beginTransaction();
                
                //Generate reference number
        
                    
                $sell_return_data['invoice_no'] = time();
                $sell_return_data['transaction_date'] = now();
                

                if (empty($sell_return)) {
                    $sell_return_data['location_id'] = $sell->location_id;
                    $sell_return_data['contact_id'] = $sell->contact_id;
                    $sell_return_data['type'] = 'sell_return';
                    $sell_return_data['user_id'] = $user_id;
                    $sell_return_data['return_parent_id'] = $sell->id;
                    $sell_return = Transaction::create($sell_return_data);
                } else {
                    $sell_return->update($sell_return_data);
                }

                

                //Update quantity returned in sell line
                $returns = [];
                $product_lines = $request->input('products');
                foreach ($product_lines as $product_line) {
                    $returns[$product_line['sell_line_id']] = $product_line['quantity'];
                }
                foreach ($sell->lines as $sell_line) {
                    if (array_key_exists($sell_line->id, $returns)) {
                        
                        $quantity = $returns[$sell_line->id];

                        $quantity_before = $sell_line->quantity_returned;
                        $quantity_formated = $quantity;

                        $sell_line->quantity_returned = $quantity;
                        $sell_line->save();
                        
                        $this->productUtil->updateProductStock($sell_line->product_id, $sell_line->variation_id,$sell_return->location_id, $quantity_formated, $quantity_before);
                    }
                }
                
                DB::commit();

                $output = ['status' => true,
                            'msg' => 'Successfully Returned',
                            'url' => route('sell_returns.index')
                        ];
            }
        } catch (\Exception $e) {
            DB::rollBack();

            $output = ['status' => false,
                            'msg' => $e->getMessage()
                        ];
        }

        return $output;
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        DB::beginTransaction();

        try {
            $return=Transaction::find($id);
            $transaction=Transaction::find($return->return_parent_id);
        
            foreach ($transaction->lines as $key => $line) {
                if($line->quantity_returned){
                    $this->productUtil->decreaseProductStock($line->product_id,$line->variation_id, $transaction->location_id,$line->quantity_returned);
                }
                
            }
    
            $return->payments()->delete();
            $return->vendor_orders()->delete();
            $return->delete();
            
        
            DB::commit();
            return response()->json(['status'=>true ,'msg'=>'Sell Return Deleted Successfully !!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>false ,'msg'=>$e->getMessage()]);
        }

    }
}
