<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Upazila;
use App\Models\UserAddress;
use Illuminate\Support\Facades\DB;

use App\Models\District;
use App\Models\Transaction;


class ContactAddressController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $item=UserAddress::find($id);
        
        $districts=District::get();
        
        $upazilas=Upazila::where('district_id', $item->district_id)->get();
        
        return view('customers.edit_address', compact('item','upazilas','districts'));
        
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $contact=UserAddress::find($id);
        $data=$request->validate([
            'name' => 'required',
            'phone' => 'required',
            'district_id' => 'required',
            'upazila_id' => '',
            'address' => ''
        ]);
        $contact->update($data);
        return response()->json(['status'=>true ,'msg'=>'Customer Address Updated !!']);
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $item=UserAddress::find($id);
        
        $check=Transaction::where('shipping_id', $id)->count();
        
        if($check>0){
            return response()->json(['status'=>true ,'msg'=>'Cant\'t Delete Address!!']);
        }
        $item->delete();
        
        $url=route('contacts.show',[$item->contact_id]);
        return response()->json(['status'=>true ,'msg'=>'Address Deleted !!','url'=>$url]);
        
    }
}
