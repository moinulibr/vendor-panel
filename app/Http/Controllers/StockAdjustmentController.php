<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use App\Models\TransactionLine;
use App\Models\TransactionPayment;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Location;
use App\Models\Variation;
use Illuminate\Http\Request;
use App\Utils\ProductUtil;
use Illuminate\Support\Facades\DB;

class StockAdjustmentController extends Controller
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
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            
            $search=$request->q;
            $location_id=$request->location_id;
            $date=$request->date;
            
            $query = Transaction::latest()->where(['is_new'=>0,'type'=>'stock_adjustment']);
                if($search){
                    $query->where(function($row) use($search){
                        $row->where('transactions.invoice_no', 'LIKE', '%'. $search. '%');
                        $row->orwhere('transactions.note', 'LIKE', '%'. $search. '%');
                    });
                }
                
            if($date){
                
                $dates  = explode('~', $date);
                
                $start = isset($dates[0]) ? trim($dates[0]) : null;
                $end   = isset($dates[1]) ? trim($dates[1]) : null;
                $query->whereDate('transaction_date', '>=', $start)->whereDate('transaction_date', '<=', $end);
                
            }
            
            if($location_id){
                $query->where('transactions.location_id', $location_id);
            }
            
            $items=$query->paginate(30);

            return view('stock_adjustments.data',compact('items'))->render();
        }
        
        $locations = Location::where(['is_new'=>0])->get();
        return view('stock_adjustments.index',compact('locations'));
    }
    

    public function create(){

        $transaction=Transaction::updateOrCreate(['is_new'=>1,'type'=>'stock_adjustment']);

        return $this->edit($transaction->id);
    }
    
    public function show($id)
    {
       
        $transaction=Transaction::find($id);
        return view('stock_adjustments.show',compact('transaction'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $transaction=Transaction::find($id);
        $locations = Location::where(['is_new'=>0])->get();
        return view('stock_adjustments.create', compact('transaction','locations'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id){
        $stock_adjustment=Transaction::find($id);
        $data=$request->validate([
             'note'=> '',
             'transaction_date'=> 'required',
             'final_amount'=> 'required|numeric',
             'invoice_no'=> '',
             'product_id'=> 'required',
             'location_id'=> 'required',
             'adjustment_type'=> 'required',
        ]);
        $data['is_new']=0;
        $data['type']='stock_adjustment';
        $data['user_id']=auth()->user()->id;

         DB::beginTransaction();

        try {
            unset($data['product_id']);
            $stock_adjustment->update($data);
            
            $type=$stock_adjustment->adjustment_type;

            $location_id=$stock_adjustment->location_id;
            if (isset($request->line_id)) {
                $delete_line=TransactionLine::where('transaction_id', $id)
                                ->whereNotIn('id', $request->line_id)
                                ->get();


                if ($delete_line->count()) {
                    foreach ($delete_line as $key => $line) {

                        $this->productUtil->decreaseProductStock($line->product_id,$line->variation_id, $location_id,$line->quantity);
                        
                        $line->delete();
                    }
                }
            } else if($stock_adjustment->lines){
                foreach ($stock_adjustment->lines as $key => $line) {
                    
                    $this->productUtil->decreaseProductStock($line->product_id,$line->variation_id, $location_id,$line->quantity);

                    $line->delete();
                }
            }

            
            // update stock_adjustment line and product stock
            if (isset($request->product_id)) {
                $data=[];
                foreach ($request->product_id as $key => $product_id) {
                    //product stock increase/decrease

                    $variation_id=$request->variation_id[$key];
                    if (isset($request->line_id[$key])) {

                        

                        $qty=$request->quantity[$key];
                        $line_id=$request->line_id[$key];
                        $line=TransactionLine::find($line_id);
                        $this->productUtil->updateProductStock($line->product_id,$line->variation_id, $location_id,$line->quantity,$qty);

                        $line->quantity=$qty;
                        $line->price=$request->unit_price[$key];
                        $line->save();

                    }
                    //product stock increase
                    else{
                        $qty=$request->quantity[$key];
                        $data[]=[
                            'product_id'=>$product_id,
                            'variation_id'=>$variation_id,
                            'quantity'=>$qty,
                            'price'=>$request->unit_price[$key],
                        ];
                        
                        if($type=='plus'){
                            $this->productUtil->increaseProductStock($product_id,$variation_id, $location_id,$qty); 
                        }else{
                            $this->productUtil->decreaseProductStock($product_id,$variation_id, $location_id,$qty); 
                        }

                                        
                    }
                    
                }
                if (!empty($data)) {
                    $stock_adjustment->lines()->createMany($data);
                }
                
            }

            

            if(isset($request->pay_amount)){

                foreach ($request->pay_amount as $key => $amount) {
                    if(isset($request->pay_id[$key])){
                        $pay=TransactionPayment::find($request->pay_id[$key]);
                    }else{
                        $pay=new TransactionPayment();
                    }
                    
                    $pay->transaction_id=$stock_adjustment->id;
                    $pay->paid_on=$stock_adjustment->date;
                    $pay->method=$request->method[$key];
                    $pay->amount=$amount;
                    $pay->note=$request->pay_note[$key];
                    $pay->date=newdate($data['date']);
                    $pay->save();

                }
            }


            DB::commit();
            return response()->json(['status'=>true ,'msg'=>'Stock Adjustment Added !!','function'=> 'ss']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>false ,'msg'=>$e->getMessage()]);
        }

    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $stock_adjustment=Transaction::find($id); 
        $type=$stock_adjustment->adjustment_type;
        foreach ($stock_adjustment->lines as $key => $line) {
            
            if($type=='plus'){
                $this->productUtil->decreaseProductStock($line->product_id,$line->variation_id, $stock_adjustment->location_id,$line->quantity);
            }else{
                $this->productUtil->increaseProductStock($line->product_id,$line->variation_id, $stock_adjustment->location_id,$line->quantity);
            }
            
            $line->delete();
        }

        $stock_adjustment->payments()->delete();
        $stock_adjustment->delete();
        return response()->json(['status'=>true ,'msg'=>'Deleted Successfully !!']);

    }


    public function getAdjustmentProduct(Request $request){

        $adjustment_type=$request->adjustment_type;
        $location_id=$request->location_id;
        $search=$request->get('search');
        $query=Variation::join('products as p' ,'p.id','variations.product_id')
                    ->join('product_stocks as ps' ,'ps.variation_id','variations.id')
                    ->select('variations.id',DB::raw("CONCAT(p.name, ' ', variations.name) as value"),

                        DB::raw("SUM(ps.qty_available) AS stock")
                    )
                    
                    ->where('p.stock_manage',1)
                    ->where(function($row) use($search){
                        
                        $row->where('p.name', 'LIKE', '%'. $search. '%');
                        $row->orwhere('p.sku', 'LIKE', '%'. $search. '%');
                    });
                    
                    if($adjustment_type=='minus'){
                        $query->having('stock','>',0)
                        ->where('ps.location_id', $location_id);
                    }
                
                    
        $data=$query->groupBy('variations.id')
                    ->get();
        return response()->json($data);

    }


    public function adjustmentProductEntry(Request $request){

        $id=$request->id;
        $location_id=$request->location_id;
        $adjustment_type=$request->adjustment_type;

        $query=Variation::join('products as p' ,'p.id','variations.product_id')
                    ->join('product_stocks as ps' ,'ps.variation_id','variations.id')
                    ->select('variations.*','ps.qty_available','p.name as product_name','p.type',

                        DB::raw("SUM(ps.qty_available) AS stock")
                    )
                    ->where('variations.id', $id);
                    
                if($adjustment_type=='minus'){
                    $query->where('ps.location_id', $location_id)
                    ->having('stock','>',0);
                }
                    
        $item=$query->first();
        if ($item) {
            $html=view('stock_adjustments.product_row', compact('item','adjustment_type'))->render();

            return response()->json(['success'=>true,'html'=>$html]);
        }else{
            return response()->json(['success'=>false,'msg'=>'Stock Not Available !!']);
        }
        
        
    }


}
