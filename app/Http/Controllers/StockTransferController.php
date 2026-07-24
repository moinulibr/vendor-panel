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

class StockTransferController extends Controller
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
            $location_id_to=$request->location_id_to;
            $location_id=$request->location_id;
            $date=$request->date;
            $query = Transaction::latest()->where(['is_new'=>0,'type'=>'stock_transfer']);
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
            
            if($location_id_to){
                $query->where('transactions.location_id_to', $location_id_to);
            }
            
            if($location_id){
                $query->where('transactions.location_id', $location_id);
            }
            
            $items=$query->paginate(30);
            return view('stock_transfers.data',compact('items'))->render();
        }
        
        $locations = Location::where(['is_new'=>0])->get();
        return view('stock_transfers.index',compact('locations'));
    }
    

    public function create(){

        $transaction=Transaction::updateOrCreate(['is_new'=>1,'type'=>'stock_transfer']);

        return $this->edit($transaction->id);
    }
    
    public function show($id)
    {
       
        $transaction=Transaction::find($id);
        return view('stock_transfers.show',compact('transaction'));
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

        $contacts = Contact::where(['is_new'=>0,'type'=>'supplier'])->get();
        $locations = Location::where(['is_new'=>0])->get();
        
        
        return view('stock_transfers.create', compact('transaction','contacts','locations'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id){
        $stock_transfer=Transaction::find($id);
        $data=$request->validate([
             'note'=> '',
             'transaction_date'=> 'required',
             'final_amount'=> 'required|numeric',
             'invoice_no'=> '',
             'product_id'=> 'required',
             'location_id'=> 'required',
             'location_id_to'=> 'required'
        ]);
        $data['is_new']=0;
        $data['type']='stock_transfer';
        $data['user_id']=auth()->user()->id;

         DB::beginTransaction();

        try {
            unset($data['product_id']);
            $stock_transfer->update($data);

            $location_id=$stock_transfer->location_id;
            $location_id_to=$stock_transfer->location_id_to;
            if (isset($request->line_id)) {
                $delete_line=TransactionLine::where('transaction_id', $id)
                                ->whereNotIn('id', $request->line_id)
                                ->get();


                if ($delete_line->count()) {
                    foreach ($delete_line as $key => $line) {

                        $this->productUtil->decreaseProductStock($line->product_id,$line->product_id, $location_id,$line->quantity);
                        
                        $line->delete();
                    }
                }
            } else if($stock_transfer->lines){
                foreach ($stock_transfer->lines as $key => $line) {
                    
                    $this->productUtil->decreaseProductStock($line->product_id,$line->product_id, $location_id,$line->quantity);

                    $line->delete();
                }
            }

            
            // update stock_transfer line and product stock
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
                        
              
                        $this->productUtil->increaseProductStock($product_id,$variation_id, $location_id_to,$qty); 
                  
                        $this->productUtil->decreaseProductStock($product_id,$variation_id, $location_id,$qty); 
                        

                                        
                    }
                    
                }
                if (!empty($data)) {
                    $stock_transfer->lines()->createMany($data);
                }
                
            }


            DB::commit();
            return response()->json(['status'=>true ,'msg'=>'Transfer Is  Updated !!','function'=> 'ss']);
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

        $stock_transfer=Transaction::find($id); 
        
        $location_id=$stock_transfer->location_id;
        $location_id_to=$stock_transfer->location_id_to;
        foreach ($stock_transfer->lines as $key => $line) {
            
           
            $this->productUtil->decreaseProductStock($line->product_id,$line->variation_id, $location_id_to,$line->quantity);
            $this->productUtil->increaseProductStock($line->product_id,$line->variation_id, $location_id,$line->quantity);
            
            
            $line->delete();
        }

        $stock_transfer->payments()->delete();
        $stock_transfer->delete();
        return response()->json(['status'=>true ,'msg'=>'Deleted Successfully !!']);

    }


    public function getTransferProduct(Request $request){

        $location_id=$request->location_id;
        $search=$request->get('search');
        $data=Variation::join('products as p' ,'p.id','variations.product_id')
                    ->join('product_stocks as ps' ,'ps.variation_id','variations.id')
                    ->select('variations.id',DB::raw("CONCAT(p.name, ' ', variations.name) as value"),

                        DB::raw("SUM(ps.qty_available) AS stock")
                    )
                    ->where('p.stock_manage',1)
                    ->where(function($row) use($search){
                        
                        $row->where('p.name', 'LIKE', '%'. $search. '%');
                        $row->orwhere('p.sku', 'LIKE', '%'. $search. '%');
                    })
                    ->having('stock','>',0)
                    ->where('ps.location_id', $location_id)
                    ->groupBy('variations.id')
                    ->get();
        return response()->json($data);

    }


    public function transferProductEntry(Request $request){

        $id=$request->variation_id;
        $location_id=$request->location_id;

        $item=Variation::join('products as p' ,'p.id','variations.product_id')
                    ->join('product_stocks as ps' ,'ps.variation_id','variations.id')
                    ->select('variations.*','ps.qty_available','p.name as product_name',

                        DB::raw("SUM(ps.qty_available) AS stock")
                    )
                    ->having('stock','>',0)
                    ->where('ps.location_id', $location_id)
                    ->where('variations.id', $id)
                    ->first();
        if ($item) {
            $html=view('stock_transfers.product_row', compact('item'))->render();

            return response()->json(['success'=>true,'html'=>$html]);
        }else{
            return response()->json(['success'=>false,'msg'=>'Stock Not Available !!']);
        }
    }


}
