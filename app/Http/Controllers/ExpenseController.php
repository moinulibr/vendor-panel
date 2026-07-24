<?php

namespace App\Http\Controllers;
use App\Models\Transaction;
use App\Models\TransactionPayment;
use App\Models\TransactionCategory;
use App\Models\Contact;
use App\Models\Location;
use Illuminate\Http\Request;
use App\Utils\ProductUtil;
use Illuminate\Support\Facades\DB;

class ExpenseController extends Controller
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
            $type=$request->type;
            $category_id=$request->category_id;
            $payment_status=$request->payment_status;
            $date=$request->date;
            
            $query = Transaction::latest()->where(['is_new'=>0]);
                if($search){
                    $query->where(function($row) use($search){
                        $row->where('transactions.invoice_no', 'LIKE', '%'. $search. '%');
                        $row->orwhere('transactions.note', 'LIKE', '%'. $search. '%');
                    });
                }
                
            if($type){
                $query->where('type', $type);
            }
            
            if($category_id){
                $query->where('category_id', $category_id);
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

            return view('expenses.data',compact('items', 'type'))->render();
            
        }
        
        $cats = TransactionCategory::where(['is_new'=>0,'type'=>'expense'])->get();
        return view('expenses.index', compact('cats'));
    }
    

    public function create(){

        $transaction=Transaction::updateOrCreate(['is_new'=>1,'type'=>'expense']);

        return $this->edit($transaction->id);
    }
    
    public function show($id)
    {
       
        $transaction=Transaction::with('pyments','payments.user')->find($id);
        return view('expenses.show',compact('transaction'));
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

        $cats = TransactionCategory::where(['is_new'=>0,'type'=>'expense'])->get();
        $locations = Location::where(['is_new'=>0])->get();
        return view('expenses.create', compact('transaction','cats','locations'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id){
        $expense=Transaction::find($id);
        $data=$request->validate([
             'note'=> '',
             'transaction_date'=> 'required',
             'final_amount'=> 'required|numeric',
             'invoice_no'=> '',
             'location_id'=> 'required',
             'category_id'=> 'required',
        ]);
        $data['is_new']=0;
        $data['type']='expense';
        $data['user_id']=auth()->user()->id;

         DB::beginTransaction();

        try {
            unset($data['product_id']);
            $expense->update($data);

            $location_id=$expense->location_id;

            if(isset($request->pay_amount)){

                foreach ($request->pay_amount as $key => $amount) {
                    if(isset($request->pay_id[$key])){
                        $pay=TransactionPayment::find($request->pay_id[$key]);
                    }else{
                        $pay=new TransactionPayment();
                    }
                    
                    $pay->transaction_id=$expense->id;
                    $pay->paid_on=$expense->date;
                    $pay->method=$request->method[$key];
                    $pay->amount=$amount;
                    $pay->note=$request->pay_note[$key];
                    $pay->date=newdate($data['date']);
                    $pay->save();

                }
            }

            $this->productUtil->transactionStatus($expense);

            DB::commit();
            return response()->json(['status'=>true ,'msg'=>'Expense Is  Updated !!','function'=> 'ss']);
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

        $purchase->payments()->delete();
        $purchase->delete();
        return response()->json(['status'=>true ,'msg'=>'Deleted Successfully !!']);

    }

    public function expenseCategory(){

        return view('expenses.categories');

    }




}
