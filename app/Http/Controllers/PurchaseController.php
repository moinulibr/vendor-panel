<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use App\Models\TransactionLine;
use App\Models\TransactionPayment;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Location;
use App\Models\User;
use App\Models\Variation;
use Illuminate\Http\Request;
use App\Utils\ProductUtil;
use Illuminate\Support\Facades\DB;

class PurchaseController extends Controller
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
            $date=$request->date;
            $contact_id=$request->contact_id;
            
            $payment_status=$request->payment_status;
            
            $query = Transaction::latest()->where(['is_new'=>0,'type'=>'purchase']);
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
                
                if($contact_id){
                    $query->where('contact_id', $contact_id);
                }
            
                if($payment_status){
                    $query->where('payment_status', $payment_status);
                }
            
            $items=$query->paginate(30);

            return view('purchases.data',compact('items'))->render();
        }
        
        $contacts = Contact::where(['is_new'=>0,'type'=>'supplier'])->get();

        return view('purchases.index',compact('contacts'));
    }
    

    public function create(){

        $transaction=Transaction::updateOrCreate(['is_new'=>1,'type'=>'purchase']);

        return $this->edit($transaction->id);
    }
    
    public function show($id)
    {
       
        $transaction=Transaction::find($id);
        return view('purchases.show',compact('transaction'));
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

        $contacts = [];
        
        if($transaction->vendor_id){
            $contacts = Contact::where(['is_new'=>0,'type'=>'supplier','user_id'=>$transaction->vendor_id])->get();
        }
        
        
        $locations = Location::where(['is_new'=>0])->get();
        
        $query_user = User::role(['Vendor','Admin']);
                    if(getRole()=='Vendor'){
                        $query_user->whereId(Auth::id());
                    }
        $users=$query_user->get();
        
        return view('purchases.create', compact('transaction','contacts','locations','users'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id){
        $purchase=Transaction::find($id);
        $location_id = $purchase->location_id;
        $isNew = $purchase->is_new == 1;
        
        $data=$request->validate([
             'note'=> '',
             'transaction_date'=> 'required',
             'final_amount'=> 'required|numeric',
             'invoice_no'=> '',
             'shipping_status'=> 'required',
             'product_id'=> 'required',
             'location_id'=> 'required',
             'contact_id'=> 'required',
             'vendor_id'=> 'required',
        ]);
        $data['is_new']=0;
        $data['type']='purchase';
        $data['user_id']=auth()->user()->id;

         DB::beginTransaction();

        try {
            unset($data['product_id']);
        
            
            $nlocation_id = $request->location_id;
            
            if (!$isNew && $nlocation_id != $location_id) {
                throw new \Exception("Can't Change Location");
            }
            
            $old_status = $purchase->shipping_status; // 'pending' or 'received'
            $new_status = $request->shipping_status;
            $purchase->update($data);
            
     
            $existing_lines = TransactionLine::where('transaction_id', $id)->get()->keyBy('id');
            $requested_line_ids = array_filter($request->input('line_id', []));
            
            // 1. Handle Deletions
            $lines_to_delete = $existing_lines->diffKeys(array_flip($requested_line_ids));
            foreach ($lines_to_delete as $line) {
            // If it was already received, we must remove it from stock before deleting the line
            if ($old_status == 'received') {
                $this->productUtil->decreaseProductStock($line->product_id, $line->variation_id, $nlocation_id, $line->quantity);
            }
            $line->delete();
            }
            
            // 2. Process Updates and Additions
            if ($request->has('product_id')) {
            $new_lines_data = [];
            
            foreach ($request->product_id as $key => $product_id) {
                $qty = $request->quantity[$key];
                $variation_id = $request->variation_id[$key];
                $unit_price = $request->unit_price[$key];
                $line_id = $request->line_id[$key] ?? null;
            
                if ($line_id && isset($existing_lines[$line_id])) {
                    $line = $existing_lines[$line_id];
                    
                    // Manage Stock based on Status change
                    if ($old_status == 'received' && $new_status == 'received') {
                        // Adjust difference
                        $this->productUtil->updateProductStock($line->product_id, $line->variation_id, $nlocation_id, $qty, $line->quantity);
                    } elseif ($old_status == 'pending' && $new_status == 'received') {
                        // Just add full quantity
                        
                        $this->productUtil->increaseProductStock($line->product_id, $line->variation_id, $nlocation_id, $qty);
                        
                    } elseif ($old_status == 'received' && $new_status == 'pending') {
                        // Reverse/Remove full quantity
                        $this->productUtil->decreaseProductStock($line->product_id, $line->variation_id, $nlocation_id, $line->quantity);
                    }
            
                    $line->update(['quantity' => $qty, 'price' => $unit_price]);
                } else {
                    // NEW LINES
                    if ($new_status == 'received') {
                        $this->productUtil->increaseProductStock($product_id, $variation_id, $nlocation_id, $qty);
                    }
                    
                    $new_lines_data[] = [
                        'product_id' => $product_id,
                        'variation_id' => $variation_id,
                        'quantity' => $qty,
                        'price' => $unit_price,
                    ];
                }
            }
            
            if (!empty($new_lines_data)) {
                $purchase->lines()->createMany($new_lines_data);
            }
            }
            
            // 3. Finally update the purchase status
            $purchase->shipping_status = $new_status;
            $purchase->save();


            

            if(isset($request->pay_amount)){

                foreach ($request->pay_amount as $key => $amount) {
                    if(isset($request->pay_id[$key])){
                        $pay=TransactionPayment::find($request->pay_id[$key]);
                    }else{
                        $pay=new TransactionPayment();
                    }
                    
                    $pay->transaction_id=$purchase->id;
                    $pay->paid_on=$purchase->date;
                    $pay->method=$request->method[$key];
                    $pay->amount=$amount;
                    $pay->note=$request->pay_note[$key];
                    $pay->date=newdate($data['date']);
                    $pay->save();

                }
            }

            $this->productUtil->transactionStatus($purchase);
            $msg = $isNew ? 'Purchase Created !!' : 'Purchase Updated !!';
            DB::commit();
            return response()->json(['status'=>true ,'msg'=>$msg,'function'=> 'getData',
                'page' => 2]);
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

        $purchase=Transaction::find($id); 

        foreach ($purchase->lines as $key => $line) {
            
            if ($purchase->shipping_status == 'received') {
                
                $this->productUtil->decreaseProductStock($line->product_id,$line->variation_id, $purchase->location_id,$line->quantity);
            }
            $line->delete();
        }

        $purchase->payments()->delete();
        $purchase->delete();
        return response()->json(['status'=>true ,'msg'=>'Deleted Successfully !!']);

    }


    public function getPurchaseProduct(Request $request){

        $search= $request->get('search');
        $query=DB::table('variations as v')
                        ->join('products as p','p.id','v.product_id')
                        ->where('p.stock_manage',1)
                        ->where(function($row) use($search){
                            
                            $row->where('p.name', 'LIKE', '%'. $search. '%')
                                ->orwhere('p.sku', 'LIKE', '%'. $search. '%')
                                ->orwhere('v.sub_sku', 'LIKE', '%'. $search. '%');
                        })
                        ->select('p.name as text','p.sku','p.image','p.created_at','v.id','v.sub_sku','p.type','v.purchase_price','v.sell_price',
                        
                        DB::raw("CASE 
                                WHEN p.type = 'variable' 
                                THEN CONCAT(p.name, ' - ', v.name)
                                ELSE p.name
                            END AS value
                        "));

        $data = $query->latest()->get();

        return response()->json($data); 

    }


    public function purchaseProductEntry(Request $request){

        $id=$request->id;
        $item=Variation::with('product')->find($id);

        if ($item) {
            $html=view('purchases.product_row', compact('item'))->render();

            return response()->json(['success'=>true,'html'=>$html]);
        }else{
            return response()->json(['success'=>false,'msg'=>'Product Note Found !!']);
        }
    }
    
    public function getSupplier(){
        
        $subcategories = Contact::where('user_id', request('vendor_id'))->where('type','supplier')->get();
        return response()->json($subcategories);
    
        
    }


}
