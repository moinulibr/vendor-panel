<?php

namespace App\Http\Controllers;

use App\Models\ProductFeature;
use Illuminate\Http\Request;

class ProductFeatureController extends Controller
{
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $search=$request->q;
            $status=$request->status;
            
            $query = ProductFeature::latest()->where('is_new',0);
            
            if($search){
                $query->where(function($row) use($search){
                    $row->where('name', 'LIKE', '%'. $search. '%');
                });
            }
            
            if($status!=''){
                $query->where('status', $status);
            }
            
            $items=$query->paginate(30);
            return view('product_features.data',compact('items'))->render();
        }

        return view('product_features.index');
    }
    

    public function create(){

        $product_feature=ProductFeature::updateOrCreate(['is_new'=>1]);
        return view('product_features.create', compact('product_feature'));
    }

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(ProductFeature $product_feature)
    {
        return view('product_features.create', compact('product_feature'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, ProductFeature $product_feature){
        $isNew = $product_feature->is_new == 1;

        $data = $request->validate([
            'name' => 'required',
            'status' => '',
        ]);
    
        $data['is_new'] = 0;
        $product_feature->update($data);
    
        $msg = $isNew ? 'Top Menu Created !!' : 'Top Menu Updated !!';
    
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
    public function destroy($id){

        $cat=ProductFeature::find($id);
        $cat->delete();
    
        return response()->json(['status'=>true ,'msg'=>'Top Menu Deleted !!']);
    }
    
    
}
