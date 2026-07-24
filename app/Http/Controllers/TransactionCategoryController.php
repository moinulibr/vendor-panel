<?php

namespace App\Http\Controllers;

use App\Models\TransactionCategory;
use Illuminate\Http\Request;
use App\Utils\ProductUtil;


class TransactionCategoryController extends Controller
{
    public $productUtil;
    function __construct(ProductUtil $productUtil)
    {
        // $this->middleware('permission:units.view|units.create|units.edit|units.delete', ['only' => ['index','show']]);
        // $this->middleware('permission:units.create', ['only' => ['create','store']]);
        // $this->middleware('permission:units.edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:units.delete', ['only' => ['destroy']]);
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
            
            $type=$request->type;
            $search=$request->q;
            $status=$request->status;
            $sort  = $request->sort;

            $query = TransactionCategory::whereType($type)->where('is_new',0);
            
            if($query){
                $query->where(function($row) use($search){
                    $row->where('name', 'LIKE', '%'. $search. '%');
                });
            }
            
            if($status!=''){
                $query->where('status', $status);
            }
            
            if ($sort == 'asc') {
                $query->orderBy('id', 'asc');
            } elseif ($sort == 'desc') {
                $query->orderBy('id', 'desc');
            } else {
                $query->latest();
            }
            
            $items=$query->paginate(30);
            
            return view('transaction_categories.data',compact('items'))->render();
        }

        return view('transaction_categories.index');
    }
    

    public function create(){
        $type=request('type');
        $unit=TransactionCategory::updateOrCreate(['type'=>$type,'is_new'=>1,'name'=>null]);

        return $this->edit($unit);
    }
    
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(TransactionCategory $transaction_category)
    {
        $unit=$transaction_category;
        return view('transaction_categories.create', compact('unit'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, TransactionCategory $transaction_category){
        $isNew = $transaction_category->is_new == 1;

        $data = $request->validate([
            'name'   => 'required',
            'status' => 'nullable',
        ]);
    
        $data['is_new'] = 0;
    
        $transaction_category->update($data);
    
        $msg = $isNew ? 'Category Created !!' : 'Category Updated !!';
    
        return response()->json([
            'status'   => true,
            'msg'      => $msg,
            'function' => 'getData'
        ]);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $unit=TransactionCategory::find($id);
        $unit->delete();
    
        return response()->json(['status'=>true ,'msg'=>'Category Deleted !!']);
    }


}
