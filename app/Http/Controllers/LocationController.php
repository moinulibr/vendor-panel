<?php

namespace App\Http\Controllers;

use App\Models\Location;
use Illuminate\Http\Request;
use App\Utils\ProductUtil;

class LocationController extends Controller
{
    function __construct(ProductUtil $productUtil)
    {
        // $this->middleware('permission:locations.view|locations.create|locations.edit|locations.delete', ['only' => ['index','show']]);
        // $this->middleware('permission:locations.create', ['only' => ['create','store']]);
        // $this->middleware('permission:locations.edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:locations.delete', ['only' => ['destroy']]);
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
            
            $query = Location::withoutGlobalScope('active')->where('is_new', 0);
            
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

            return view('locations.data',compact('items'))->render();
        }

        return view('locations.index');
    }
    

    public function create(){

        $location=Location::updateOrCreate(['is_new'=>1,'name'=>null]);
        return view('locations.create', compact('location'));
    }
    
    public function show(Location $product): View
    {
        return view('locations.show',compact('product'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Location $location)
    {
        return view('locations.create', compact('location'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Location $location){
        $data = $request->validate([
            'name' => 'required',
            'code' => 'required',
            'mobile' => 'required',
            'email' => '',
            'address' => 'required',
            'status' => '',
        ]);

        $isNew = $location->is_new == 1;
        
        $data['is_new'] = 0;
        
        $image = $this->productUtil->FileUpload($request, 'image', 'locations');
        if ($image) {
            $data['image'] = $image;
        }
        
        $location->update($data);
        
        $msg = $isNew ? 'Location Created !!' : 'Location Updated !!';
        
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

        $location=Location::find($id);
        deleteImage('locations',$location->image);
        $location->delete();
    
        return response()->json(['status'=>true ,'msg'=>'Location Deleted !!']);
    }

}
