<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use Illuminate\Http\Request;
use App\Utils\ProductUtil;
use DB;


class BrandController extends Controller
{
    function __construct(ProductUtil $productUtil)
    {
        // $this->middleware('permission:brands.view|brands.create|brands.edit|brands.delete', ['only' => ['index','show']]);
        // $this->middleware('permission:brands.create', ['only' => ['create','store']]);
        // $this->middleware('permission:brands.edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:brands.delete', ['only' => ['destroy']]);
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
            
            $query = Brand::latest()->where('is_new',0);
            
            if($search){
                $query->where(function($row) use($search){
                    $row->where('name', 'LIKE', '%'. $search. '%');
                });
            }
            
            if($status!=''){
                $query->where('status', $status);
            }
            
            $items=$query->paginate(30);
            return view('brands.data',compact('items'))->render();
        }

        return view('brands.index');
    }
    

    public function create(){

        $brand=Brand::updateOrCreate(['is_new'=>1,'name'=>null]);
        return view('brands.create', compact('brand'));
    }
    
    public function show(Brand $product): View
    {
        return view('brands.show',compact('product'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Brand $brand)
    {
        return view('brands.create', compact('brand'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Brand $brand)
    {

        $isNew = $brand->is_new == 1;
    
        $data = $request->validate([
            'name' => 'required',
            'bd_name' => 'nullable',
            'status' => 'required|in:0,1',
        ]);
    
        $data['is_new'] = 0;
    
        $image = $this->productUtil->FileUpload($request, 'image', 'brands');
        if ($image) {
            $data['image'] = $image;
        }
    
        $brand->update($data);
    
        $msg = $isNew ? 'Brand Created !!' : 'Brand Updated !!';
    
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

        $brand=Brand::find($id);
        deleteImage('brands',$brand->image);
        $brand->delete();
    
        return response()->json(['status'=>true ,'msg'=>'Brand Deleted !!']);
    }

    public function brandStatus(Request $request){

        $name='is_top';
        if (isset($request->is_home)) {
            $name='is_home';
        }else if (isset($request->is_menu)) {
            $name='is_menu';
        }
        

        $ids=$request->product_ids;

        $status=(request($name)==1)?1:0;

        DB::table('brands')->whereIn('id', $ids)->update([$name=>$status]);


        return response()->json(['status'=>true ,'msg'=>'Product Updated Status !!']);
    }

}
