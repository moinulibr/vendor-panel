<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Product;
use App\Models\Variation;
use App\Utils\ProductUtil;


class SellController extends Controller
{
     public $productUtil;
    function __construct(ProductUtil $productUtil)
    {
        // $this->middleware('permission:products.view|products.create|products.edit|products.delete', ['only' => ['index','show']]);
        // $this->middleware('permission:products.create', ['only' => ['create','store']]);
        // $this->middleware('permission:products.edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:products.delete', ['only' => ['destroy']]);
        $this->productUtil=$productUtil;
    }
    
    
    public function index(Request $request) 
    {
        if ($request->ajax()) {
            $search=$request->q;
            $shipping_status=$request->shipping_status;
            $payment_status=$request->payment_status;
            $date=$request->date;
            
            $query = Transaction::latest()->where(['is_new'=>0,'type'=>'sell','is_pos'=>0]);
                if($search){
                    $query->where(function($row) use($search){
                        $row->where('transactions.invoice_no', 'LIKE', '%'. $search. '%');
                        $row->orwhere('transactions.note', 'LIKE', '%'. $search. '%');
                    });
                }
             
            
            if($shipping_status){
                $query->where('shipping_status', $shipping_status);
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

            return view('sells.data',compact('items'))->render();
        }

        return view('sells.index');
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
    public function show($id)
    {
        $transaction=Transaction::with('vendor_orders')->find($id);

        return view('sells.show', compact('transaction'));
    }

    public function sellPrint($id)
    {
        $transaction=Transaction::with('lines')->find($id);
        
        if($transaction->quotation==1){
            return view('sells.quotation_print', compact('transaction'));
        }else{
            return view('sells.print', compact('transaction'));
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Transaction $transaction)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Transaction $transaction)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id){
        
        DB::beginTransaction();

        try {
            $transaction=Transaction::find($id);
    
            foreach ($transaction->lines as $key => $line) {
                
                $stock_manage=Product::find($line->product_id)->stock_manage??null;
                if($stock_manage && $transaction->quotation==0 && $transaction->shipping_status !='cancelled'){
                    $this->productUtil->decreaseProductStock($line->product_id,$line->variation_id, $transaction->location_id,$line->quantity);
                }
                
                $line->delete();
            }
    
            $transaction->payments()->delete();
            $transaction->vendor_orders()->delete();
            $transaction->delete();
            
        
            DB::commit();
            return response()->json(['status'=>true ,'msg'=>'Deleted Successfully !!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>false ,'msg'=>$e->getMessage()]);
        }
        
        
    }
}
