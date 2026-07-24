<?php

namespace App\Http\Controllers;

use App\Models\Coupon;
use Illuminate\Http\Request;
use App\Utils\ProductUtil;

class CouponController extends Controller
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

            $search=$request->q;
            $status=$request->status;
            $sort  = $request->sort;
            
            $query = Coupon::where('is_new', 0);
            
            if($query){
                $query->where(function($row) use($search){
                    $row->where('title', 'LIKE', '%'. $search. '%');
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
            return view('coupons.data',compact('items'))->render();
        }

        return view('coupons.index');
    }
    

    public function create(){

        $coupon=Coupon::updateOrCreate(['is_new'=>1,'title'=>null]);
        return view('coupons.create', compact('coupon'));
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Coupon $coupon)
    {
        return view('coupons.create', compact('coupon'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Coupon $coupon){
        $data=request()->validate([
            'title' => 'required',
            'code' => 'required',
            'amount' => 'required',
            'discount_type' => 'required',
            'start' => '',
            'end' => '',
            'note' => '',
        ]);
        $data['is_new']=0;
        $data['status']=isset($request->status)?1:0;
        $coupon->update($data);
        
        return response()->json(['status'=>true ,'msg'=>'Coupon Created !!','function'=>'getData']);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $unit=Coupon::find($id);
        $unit->delete();
    
        return response()->json(['status'=>true ,'msg'=>'Coupon Deleted !!']);
    }

}
