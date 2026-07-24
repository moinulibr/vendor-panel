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

class IncomeController extends Controller
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
            $items = Transaction::latest()->where(['is_new'=>0,'type'=>'income'])->paginate(30);

            return view('income.data',compact('items'))->render();
        }
        
        $cats = TransactionCategory::where(['is_new'=>0,'type'=>'income'])->get();
        
        return view('income.index', compact('cats'));
    }
    

    public function create(){

        $transaction=Transaction::updateOrCreate(['is_new'=>1,'type'=>'income']);

        return $this->edit($transaction->id);
    }
    
    public function show($id)
    {
       
        $transaction=Transaction::find($id);
        return view('income.show',compact('transaction'));
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

        $cats = TransactionCategory::where(['is_new'=>0,'type'=>'income'])->get();
        $locations = Location::where(['is_new'=>0])->get();
        return view('income.create', compact('transaction','cats','locations'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id){
        $income=Transaction::find($id);
        $data=$request->validate([
             'note'=> '',
             'transaction_date'=> 'required',
             'final_amount'=> 'required|numeric',
             'invoice_no'=> '',
             'location_id'=> 'required',
             'category_id'=> 'required',
        ]);
        $data['is_new']=0;
        $data['type']='income';
        $data['user_id']=auth()->user()->id;

         DB::beginTransaction();

        try {
            unset($data['product_id']);
            $income->update($data);

            $location_id=$income->location_id;

            if(isset($request->pay_amount)){

                foreach ($request->pay_amount as $key => $amount) {
                    if(isset($request->pay_id[$key])){
                        $pay=TransactionPayment::find($request->pay_id[$key]);
                    }else{
                        $pay=new TransactionPayment();
                    }
                    
                    $pay->transaction_id=$income->id;
                    $pay->paid_on=$income->date;
                    $pay->method=$request->method[$key];
                    $pay->amount=$amount;
                    $pay->note=$request->pay_note[$key];
                    $pay->date=newdate($data['date']);
                    $pay->save();

                }
            }

            $this->productUtil->transactionStatus($income);

            DB::commit();
            return response()->json(['status'=>true ,'msg'=>'Income Is  Updated !!','function'=> 'ss']);
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

        $income=Transaction::find($id); 

        $income->payments()->delete();
        $income->delete();
        return response()->json(['status'=>true ,'msg'=>'Deleted Successfully !!']);

    }

    public function incomeCategory(){

        return view('income.categories');

    }




}
