<?php

namespace App\Http\Controllers;

use App\Models\Transaction;
use App\Models\TransactionLine;
use App\Models\Contact;
use App\Models\Product;
use App\Models\Category;
use App\Models\District;
use App\Models\Discount;
use App\Models\Brand;
use App\Models\Variation;
use App\Models\TransactionPayment;
use App\Models\ContactNextPayment;
use App\Models\OrderFrom;
use App\Models\TransactionPrint;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Utils\ProductUtil;
use App\Utils\TransactionUtil;
use Illuminate\Support\Facades\DB;

class PosController extends Controller
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
            $is_pos=$request->is_pos;
            $quotation=$request->quotation??0;
            $shipping_status=$request->shipping_status;
            $payment_status=$request->payment_status;
            $online=$request->online;
            $date=$request->date;
            $contact_id=$request->contact_id;
            $order_from_id=$request->order_from_id;
            
            $query = Transaction::latest()->where(['is_new'=>0,'type'=>'sell','is_pos'=>1]);
                if($search){
                    $query->where(function($row) use($search){
                        $row->where('transactions.invoice_no', 'LIKE', '%'. $search. '%');
                        $row->orwhere('transactions.note', 'LIKE', '%'. $search. '%');
                    });
                }
            if($contact_id){
                $query->where('transactions.contact_id', $contact_id);
            }
            
            if($order_from_id){
                $query->where('transactions.order_from_id', $order_from_id);
            }
            
            if($quotation){
                $query->where('quotation',1);
            }else{
                $query->where('quotation',0);
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

            return view('pos.data',compact('items','quotation'))->render();
        }
        
        $order_froms =OrderFrom::latest()->get();
        return view('pos.index', compact('order_froms'));
    }
    
    public function getQuotation(Request $request){
        
         return view('sells.quotation');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $transaction=Transaction::updateOrCreate(['is_new'=>1,'type'=>'sell']);

        return $this->edit($transaction->id);
        
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
        $transaction=Transaction::with('lines')->find($id);
        
        return view('pos.show', compact('transaction'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id)
    {
        $transaction=Transaction::find($id);
        $contacts = Contact::where(['is_new'=>0,'type'=>'customer'])->get();
        $locations = Location::where(['is_new'=>0])->get();
        $cats = Category::latest()->where(['is_new'=>0,'parent_id'=>null])->get();
        $brands = Brand::latest()->where('is_new',0)->get();

        $olditems=TransactionLine::from('transaction_lines as tl')
                    ->join('variations' ,'variations.id','tl.variation_id')
                    ->join('products as p' ,'p.id','tl.product_id')
                    
                    ->leftJoin('product_stocks as ps', function ($join) use($transaction) {
                        $join->on('ps.variation_id', '=', 'variations.id')
                             ->where('ps.location_id', $transaction->location_id);
                    })
    
                    ->select('tl.price','tl.old_price','tl.discount','tl.discount_id','variations.*','ps.qty_available','p.stock_manage','p.name as product_name','p.type', 'p.image as product_image','tl.id as line_id','tl.quantity as ordered_qty',

                        DB::raw("COALESCE(SUM(ps.qty_available + tl.quantity), 0) AS stock")
                    )
                    ->where('tl.transaction_id', $id)
                    ->groupBy('tl.id')
                    ->get();
        
        $districts=District::get();
        
        $payments[]=[
            
                    'id'=>'',
                    'amount'=>'',
                    'method'=>'cash',
                    'transaction_no'=>'',
                    'provider'=>'',
                    'mobile_no'=>'',
                    'account_no'=>'',
                    'bank_name'=>'',
                    'card_title'=>'',
                    'card_number'=>'',
                    'note'=>'',
                    'paid_od'=>date('Y-m-d')
            ];
            
        if($transaction->payments->count()){
            $payments=$transaction->payments->toArray();
        }
        
        $order_froms =OrderFrom::latest()->get();
        return view('pos.create', compact('order_froms','contacts','transaction','locations','cats','brands','olditems','districts','payments'));
    }

    /**
     * Update the specified resource in storage.
     */
    
    public function update(Request $request, string $id){
        $transaction = Transaction::with('lines')->findOrFail($id);
        $wasNew = (int) $transaction->is_new === 1;
        $oldQuotation = (int) $transaction->quotation;

        $data = $request->validate([
            'contact_id' => 'required',
            'location_id' => 'required',
            'product_id' => 'required|array',
            'final_amount' => 'required|numeric',
            // ... include other fields as needed
        ], [
            'contact_id.required' => 'Please Select A Customer',
        ]);

        // Prepare metadata
        $data = array_merge($data, [
            'is_new' => 0,
            'is_pos' => 1,
            'type' => 'sell',
            'sms_notification' => $request->filled('sms_notification') ? 1 : null,
            'mail_notification' => $request->filled('mail_notification') ? 1 : null,
            'user_id' => auth()->id(),
            'transaction_date' => now(),
            'invoice_no' => $transaction->invoice_no ?: $this->productUtil->generateInvoiceNumber()
        ]);

        DB::beginTransaction();
        try {
            $transaction->update(collect($data)->except('product_id')->toArray());
            $newQuotation = (int) $transaction->quotation;
            $locationId = $transaction->location_id;

            // 1. Handle Deleted Lines (Syncing Stock)
            $submittedLineIds = $request->input('line_id', []);
            $linesToDelete = $transaction->lines()->whereNotIn('id', $submittedLineIds)->get();

            foreach ($linesToDelete as $line) {
                if ($newQuotation == 0 && $oldQuotation == 0) {
                    $this->productUtil->increaseProductStock($line->product_id, $line->variation_id, $locationId, $line->quantity);
                }
                $line->delete();
            }

            // 2. Update or Create Lines
            foreach ($request->product_id as $key => $productId) {
                $qty = $request->quantity[$key];
                $variationId = $request->variation_id[$key];
                $lineId = $request->line_id[$key] ?? null;

                if ($lineId) {
                    $line = TransactionLine::find($lineId);
                    // Stock Logic Consolidation
                    if ($newQuotation == 0 && $oldQuotation == 0) {
                        $this->productUtil->updateProductStock($line->product_id, $variationId, $locationId, $line->quantity, $qty);
                    }else if($new_quotation ==0 && $old_quotation==1){
                        $this->productUtil->decreaseProductStock($line->product_id, $variationId, $locationId, $qty);
                    }
                    
                            
                    
                    $line->update([
                        'quantity' => $qty,
                        'price' => $request->unit_price[$key]
                    ]);
                } else {
                    $transaction->lines()->create([
                        'product_id' => $productId,
                        'variation_id' => $variationId,
                        'quantity' => $qty,
                        'price' => $request->unit_price[$key],
                        'old_price' => $request->old_price[$key] ?? 0,
                        'discount' => $request->discount[$key] ?? 0,
                        'discount_id' => $request->discount_id[$key] ?? null,
                    ]);

                    if ($newQuotation == 0) {
                        $this->productUtil->decreaseProductStock($productId, $variationId, $locationId, $qty);
                    }
                }
            }

            // 3. Handle Payments
            $totalPaid = 0;
            
            $next_payment_date=$request->next_payment_date;
            
            if($newQuotation==0 && isset($request->payment)){
                foreach ($request->payment as $payment) {
                    
                    
                    
                    if($payment['id']){
                        $pay=TransactionPayment::find($payment['id']);
                    }else{
                        $pay=new TransactionPayment();
                        $pay->transaction_id=$transaction->id;
                        $pay->paid_on=date('Y-m-d');
                        $pay->user_id=auth()->user()->id;
                    }
                    
                    
                    $pay->method=$payment['method'];
                    if(isset($request->payment_option) && $request->payment_option === 'due') {
                        $pay->amount = 0;
                    }elseif(isset($request->payment_option) && $request->payment_option === 'partial'){
                        $pay->amount = $request->received_amount;
                    }else {
                        $pay->amount = $payment['pay_amount'];
                    }

                    $pay->note=$payment['note'];
                    $pay->transaction_no=$payment['transaction_no'];
                    $pay->provider=$payment['provider'];
                    $pay->account_no=$payment['account_no'];
                    $pay->bank_name=$payment['bank_name'];
                    $pay->card_title=$payment['card_title'];
                    $pay->card_number=$payment['card_number'];
                    $pay->mobile_no=$payment['mobile_no'];
                    
                    $pay->save();
                    
                    $totalPaid +=$pay->amount;
                }
                
            }
                

            // 4. Next Payment Record
            if ($request->filled('next_payment_date')) {
                ContactNextPayment::create([
                    'next_payment_date' => $request->next_payment_date,
                    'contact_id' => $transaction->contact_id,
                    'current_date' => now()->toDateString(),
                    'current_reveived_amount' => $totalPaid,
                ]);
            }

            $this->productUtil->transactionStatus($transaction);
            $this->productUtil->sendNotification($transaction);

            DB::commit();

            $view = $newQuotation == 1 ? 'sells.quotation_print' : 'sells.print';
            return response()->json([
                'status' => true,
                'msg' => $newQuotation == 1 ? 'Quotation Added' : ($wasNew ? 'Sell Added' : 'Sell Updated'),
                'print_html' => view($view, compact('transaction'))->render(),
                'url' => !$wasNew ? route('pos.create') : ''
            ]);

        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, 'msg' => $e->getMessage()], 500);
        }
    }

    
    
    public function storePosPrint(Request $request){
        
        $old=TransactionPrint::first();
        
        if($old){
            $old->lines()->delete();
            $old->delete();
        }
        $data=$request->validate([
             'note'=> '',
             'final_amount'=> 'required|numeric',
             'invoice_no'=> '',
             'product_id'=> 'required',
             'shipping_id'=> '',
             'location_id'=> 'required',
             'contact_id'=> '',
             'shipping_charge'=> '',
             'discount_type'=> '',
             'cal_discount'=> '',
             'discount_amount'=> '',
             'invoice_type'=> '',
        ],[
            'contact_id.required'=>'Please Select A Customer',
                
        ]);
     
        $data['type']='sell';
        $data['user_id']=auth()->user()->id;
        $data['transaction_date']=now();
        $data['invoice_no']=mt_rand(100000, 999999);

        DB::beginTransaction();

        try {
            unset($data['product_id']);
            $transaction=TransactionPrint::create($data);
            
            // update purchase line and product stock
            if (isset($request->product_id)) {
                $product_data=[];
                foreach ($request->product_id as $key => $product_id) {
                    //product stock increase/decrease
                    $variation_id=$request->variation_id[$key];
                    
                    $qty=$request->quantity[$key];
                    $product_data[]=[
                        'product_id'=>$product_id,
                        'variation_id'=>$variation_id,
                        'quantity'=>$qty,
                        'price'=>$request->unit_price[$key],
                    ];
                    
                    
                }
                
                if (!empty($product_data)) {
                    $transaction->lines()->createMany($product_data);
                }
                    
    
            }
            
            $msg='create for print';
            $print_html=view('sells.test_print', compact('transaction'))->render();
            

            DB::commit();
            return response()->json(['status'=>true ,'msg'=>$msg,'print_html'=>$print_html]);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>false ,'msg'=>$e->getMessage()]);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id)
    {

        DB::beginTransaction();

        try {
            $transaction=Transaction::find($id);
            
            if($transaction->sell_return){
                return response()->json(['status'=>false ,'msg'=>'Sell Return Exist']);
            }
            foreach ($transaction->lines as $key => $line) {
                
                $stock_manage=Product::find($line->product_id)->stock_manage??null;
                if($stock_manage && $transaction->quotation==0 && $transaction->shipping_status !='cancelled'){
                    $this->productUtil->increaseProductStock($line->product_id,$line->variation_id, $transaction->location_id,$line->quantity);
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
    
    public function sellBulkDelete(Request $request){
        
        DB::beginTransaction();

        try {
            $ids=$request->ids;
            
            foreach($ids as $id){
            
                $transaction=Transaction::find($id);
        
                foreach ($transaction->lines as $key => $line) {
                    
                    $stock_manage=Product::find($line->product_id)->stock_manage ??null;
                    if($stock_manage && $transaction->quotation==0 && $transaction->shipping_status !='cancelled'){
                        $this->productUtil->increaseProductStock($line->product_id,$line->variation_id, $transaction->location_id,$line->quantity);
                    }
                
                    $line->delete();
                }
        
                $transaction->payments()->delete();
                $transaction->delete();
            }
            
            DB::commit();
            return response()->json(['status'=>true ,'msg'=>'Deleted Successfully !!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>false ,'msg'=>$e->getMessage()]);
        }
        
        
        
    }


    public function getSellProduct(Request $request){
        
        $location_id=$request->location_id;
        $search=$request->get('search');
        $data=Variation::join('products as p' ,'p.id','variations.product_id')
                    ->leftJoin(DB::raw("
                        (
                            SELECT 
                                product_id, 
                                location_id,
                                SUM(qty_available) AS total_stock
                            FROM product_stocks
                            WHERE location_id = $location_id
                            GROUP BY product_id, location_id
                        ) AS ps
                    "), function ($join) {
                        $join->on('ps.product_id', '=', 'p.id')
                             ->where(function($q){
                                 $q->where('ps.total_stock', '>', 0)
                                   ->orWhereNull('p.stock_manage');
                             });
                    })
    
    
                    ->select('variations.id',DB::raw("CONCAT(p.name, ' ', variations.name) as value",'ps.total_stock')
                    )
                    ->where(function($row) use($search){
                        
                        $row->where('p.name', 'LIKE', '%'. $search. '%');
                        $row->orwhere('p.sku', 'LIKE', '%'. $search. '%');
                    })
                    
                    ->groupBy('variations.id')
                    ->get();

    
        return response()->json($data); 

    }


    public function sellProductEntry(Request $request){

        $id=$request->variation_id;
        $location_id=$request->location_id;
                    
        $item = Variation::join('products as p', 'p.id', 'variations.product_id')
                ->leftJoin(DB::raw("
                    (
                        SELECT 
                            variation_id,
                            SUM(qty_available) AS stock
                        FROM product_stocks
                        WHERE location_id = $location_id
                        GROUP BY variation_id
                    ) AS ps
                "), 'ps.variation_id', '=', 'variations.id')
                ->select(
                    'variations.*','p.stock_manage','p.type',
                    'p.name as product_name',
                    'p.image as product_image',
                    DB::raw('COALESCE(ps.stock, 0) AS stock')
                )
                ->where('variations.id', $id)
                ->where(function ($q) {
                    $q->where('ps.stock', '>', 0)
                      ->orWhereNull('p.stock_manage');   // ✔ added here
                })
                ->first();
       
        if ($item) {
            $s_product=$this->transactionUtil->getProductDiscount($item->product);
            
            $item->price=$item->sell_price-$s_product['discount_price'];
            $item->old_price=$item->sell_price;
            $item->discount=$s_product['discount_price'];
            $item->discount_id=$s_product['discount']->id ??null;
            $item->discount_object=$s_product['discount'];
            $html=view('pos.partials.product_row', compact('item','s_product'))->render();

            return response()->json(['success'=>true,'html'=>$html]);
        }else{
            return response()->json(['success'=>false,'msg'=>'Stock Not Available !!']);
        }
    }
    

    public function getPosProduct(Request $request){

        $category_id=$request->category_id;
        $brand_id=$request->brand_id;
        $location_id=$request->location_id;
        $search=$request->search;
        $query = DB::table('variations as v')
                    ->join('products as p','p.id','v.product_id')
                    ->leftjoin('brands','brands.id','p.brand_id')
                    ->leftjoin('product_stocks as ps' ,'ps.variation_id','v.id')
                    // ->where('product_stocks.location_id', $location_id)
                    ->select('brands.name as brand_name','p.id','p.name','p.sku','p.image','p.created_at','v.id as variation_id','v.sub_sku','p.type','v.purchase_price','v.sell_price','p.category_id','p.brand_id',
                        DB::raw("COALESCE(SUM(ps.qty_available), 0) AS stock")

                )
                // ->where('outlet_stocks.qty_available','>',0)
                ->where(function($row) use($search){
                    $row->where('p.name', 'LIKE', '%'. $search. '%')
                        ->orwhere('p.sku', 'LIKE', '%'. $search. '%')
                        ->orwhere('v.sub_sku', 'LIKE', '%'. $search. '%');
                });

                if ($location_id) {
                    $query->where(function($row) use($location_id){
                        $row->where('ps.location_id', $location_id)
                            ->orwhereNull('p.stock_manage');
                    });
                }

                if ($category_id) {
                    $query->where('p.category_id', $category_id);
                }

                if ($brand_id) {
                    $query->where('p.brand_id', $brand_id);
                }
                
                $items=$query->groupBy('v.id')
                            ->paginate(30);

            return view('pos.partials.product_section',compact('items'))->render();
        


    }
    
    
    public function sellStatus($id){

        $order=Transaction::find($id);
        $statuses=getStatusList();
        return view('pos.edit_status', compact('order','statuses'));

    }

    public function updateSellStatus($id){

        $order=Transaction::find($id);
        $data = request()->except('_token');
        $order->update($data);
        return response()->json(['status'=>true ,'msg'=>'Order Status Updated !!','function'=>'ggg']);

    }
    
}
