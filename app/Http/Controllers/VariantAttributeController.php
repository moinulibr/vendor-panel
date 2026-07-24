<?php

namespace App\Http\Controllers;

use App\Models\VariantAttribute;
use Illuminate\Http\Request;

class VariantAttributeController extends Controller
{
    function __construct()
    {
        // $this->middleware('permission:variant_attributes.view|variant_attributes.create|variant_attributes.edit|variant_attributes.delete', ['only' => ['index','show']]);
        // $this->middleware('permission:variant_attributes.create', ['only' => ['create','store']]);
        // $this->middleware('permission:variant_attributes.edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:variant_attributes.delete', ['only' => ['destroy']]);
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
            
            $query = VariantAttribute::latest()->where('is_new',0);
            if($search){
                $query->where(function($row) use($search){
                    $row->where('name', 'LIKE', '%'. $search. '%');
                });
            }
            
            if($status!=''){
                $query->where('status', $status);
            }
            
            $items=$query->paginate(30);
            return view('variant_attributes.data',compact('items'))->render();
        }

        return view('variant_attributes.index');
    }
    

    public function create(){

        $item=VariantAttribute::updateOrCreate(['is_new'=>1,'name'=>null]);
        return view('variant_attributes.create', compact('item'));
    }
    
    public function show(VariantAttribute $product): View
    {
        return view('variant_attributes.show',compact('product'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $item=VariantAttribute::find($id);
        return view('variant_attributes.create', compact('item'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $item = VariantAttribute::findOrFail($id);

        $isNew = $item->is_new == 1;
    
        $data = $request->validate([
            'name'   => 'required',
            'valus'  => 'required',
            'status' => 'nullable',
        ]);
    
        $data['is_new'] = 0;
    
        $item->update($data);
    
        $msg = $isNew ? 'Variant Created !!' : 'Variant Updated !!';
    
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

        $item=VariantAttribute::find($id);
        $item->delete();
    
        return response()->json(['status'=>true ,'msg'=>'Variant Deleted !!']);
    }

}
