<?php

namespace App\Http\Controllers;

use App\Models\Discount;
use App\Models\Category;
use App\Models\Brand;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Utils\ProductUtil;
use Illuminate\Support\Facades\Auth;
use App\Models\User;


class DiscountController extends Controller
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

        /* foreach (discountPriorityList() as $discount) {
            echo " - ". $discount['label'] . " - " . $discount['id'] . "<br>";
        }
        return; */
        if ($request->ajax()) {

            $search=$request->q;
            $status=$request->status;
            $sort  = $request->sort;
            
            $query = Discount::whereNull('is_product')
                    ->select(
                        'discounts.*',
                        'contacts.name as contact_name',
                        'contacts.email as contact_email',
                        'contacts.mobile as contact_mobile'
                    )
            ->leftJoin('contacts', 'contacts.user_id', '=', 'discounts.user_id')
            ->where('discounts.is_new', 0);
            
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

            return view('discounts.data',compact('items'))->render();
        }


        return view('discounts.index');
    }
    

    public function create(){

        $discount=Discount::updateOrCreate(['is_new'=>1,'title'=>null]);
        return $this->edit($discount);
    }
    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Discount $discount)
    {
        $cats = Category::where(['is_new'=>0,'parent_id'=>null])->get();
        $brands = Brand::get();
        $query_user = User::role(['Vendor']);
                    if(getRole()=='Vendor'){
                        $query_user->whereId(Auth::id());
                    }
        $users = $query_user->get();
        
        $selectedProducts = $discount->discount_prodcuts->map(function($p){
            return ['id' => $p->id, 'name' => $p->name];
        });
    

        return view('discounts.create', compact('discount','cats','brands','users','selectedProducts'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Discount $discount)
    {
        // Check if this discount is new before updating
        $isNew = $discount->is_new == 1;
    
        // Validate the request
        $data = $request->validate([
            'title' => 'required',
            'amount' => 'required',
            'discount_type' => 'required',
            'start' => '',
            'end' => '',
            'category_id' => '',
            'brand_id' => '',
            'user_id' => '',
            'priority' => '',
            'status' => '', // optional, handled below
        ]);
    
        // Mark as not new anymore
        $data['is_new'] = 0;
    
        // Set status
        $data['status'] = isset($request->status) ? 1 : 0;
    
        // Update the discount
        $discount->update($data);
        
        // Dynamic message
        $msg = $isNew ? 'Discount Created !!' : 'Discount Updated !!';
    
        return response()->json([
            'status' => true,
            'msg' => $msg,
            'function' => 'getData'
        ]);
    }

    public function show(Discount $discount)
    {
        // Load related contacts via join
        $discount = Discount::select('discounts.*', 'contacts.name as contact_name', 'contacts.email as contact_email')
            ->leftJoin('contacts', 'contacts.user_id', '=', 'discounts.user_id')
            ->where('discounts.id', $discount->id)
            ->first();
    
        return view('discounts.show', compact('discount'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $unit=Discount::find($id);
        
        if ($product->lines->count()) {
            
            return response()->json(['status'=>true ,'msg'=>"Can't Delete This Discount"]);
        }
            
        $unit->delete();
    
        return response()->json(['status'=>true ,'msg'=>'Discount Deleted !!']);
    }
    
    
    public function discountProducts(Request $request){
        $search = $request->q;
    
        $products = Product::where('name', 'LIKE', "%$search%")
            ->orwhere('sku', 'LIKE', "%$search%")
            ->select('id','name','sku')
            ->limit(20)
            ->get();
    
        return response()->json(
            $products->map(function ($p) {
                return [
                    'id' => $p->id,
                    'text' => $p->name . ' (' . $p->sku . ')'
                ];
            })
        );
    }


}
