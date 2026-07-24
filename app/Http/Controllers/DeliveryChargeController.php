<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\DeliveryCharge as Charge;

class DeliveryChargeController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            
            $search=$request->q;
            $status=$request->status;
            
            $query = Charge::latest()->where('is_new',0);
            
            if($query){
                $query->where(function($row) use($search){
                    $row->where('title', 'LIKE', '%'. $search. '%');
                });
            }
            
            if($status!=''){
                $query->where('status', $status);
            }
            
            $items=$query->paginate(30);

            return view('charges.data',compact('items'))->render();
        }

        return view('charges.index');
    }
    

    public function create(){

        $charge=Charge::updateOrCreate(['is_new'=>1]);
        return $this->edit($charge->id);
    }
    

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $charge=Charge::find($id);
        return view('charges.create', compact('charge'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $charge = Charge::findOrFail($id);

        $isNew = $charge->is_new == 1;
    
        $data = $request->validate([
            'title'       => 'required',
            'description' => 'nullable',
            'status'      => 'nullable',
            'amount'      => 'required|numeric',
        ]);
    
        $data['is_new'] = 0;
    
        $charge->update($data);
    
        $msg = $isNew ? 'Charge Created !!' : 'Charge Updated !!';
    
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

        $brand=Charge::find($id);
        $brand->delete();
    
        return response()->json(['status'=>true ,'msg'=>'Charge Deleted !!']);
    }

}
