<?php

namespace App\Http\Controllers;

use App\Models\OrderFrom;
use Illuminate\Http\Request;
use App\Utils\ProductUtil;

class OrderFromController extends Controller
{
    function __construct(ProductUtil $productUtil)
    {
        // $this->middleware('permission:order_from.view|order_from.create|order_from.edit|order_from.delete', ['only' => ['index','show']]);
        // $this->middleware('permission:order_from.create', ['only' => ['create','store']]);
        // $this->middleware('permission:order_from.edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:order_from.delete', ['only' => ['destroy']]);
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
            $status=$request->status;
            
            $query =OrderFrom::latest();
            
            if($search){
                $query->where(function($row) use($search){
                    $row->where('title', 'LIKE', '%'. $search. '%');
                });
            }
            
            if($status!=''){
                $query->where('status', $status);
            }
            
            $items=$query->paginate(30);

            return view('order_from.data',compact('items'))->render();
        }

        return view('order_from.index');
    }
    

    public function create(){

        return view('order_from.create');
    }
    
    
    public function store(Request $request){

       $data = $request->validate([
            'title' => 'required',
            'status' => 'nullable',
        ]);
        
        OrderFrom::create($data);
        
        $msg = 'OrderFrom Created !!';
    
        return response()->json([
            'status' => true,
            'msg' => $msg,
            'function' => 'getData'
        ]);
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(OrderFrom $order_from)
    {
        return view('order_from.edit', compact('order_from'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, OrderFrom $order_from)
    {


    
        $data = $request->validate([
            'title' => 'required',
            'status' => 'nullable',
        ]);
    
        $order_from->update($data);
    
        $msg = 'OrderFrom Updated !!';
    
        return response()->json([
            'status' => true,
            'msg' => $msg,
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

        $order_from=OrderFrom::find($id);
        $order_from->delete();
    
        return response()->json(['status'=>true ,'msg'=>'OrderFrom Deleted !!']);
    }

}
