<?php

namespace App\Http\Controllers;

use App\Models\SiteVisit;
use App\Models\Category;
use App\Models\Brand;
use Illuminate\Http\Request;
use App\Utils\ProductUtil;
use Illuminate\Support\Facades\Auth;


class SiteVisitController extends Controller
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
            
            $query = SiteVisit::where('is_new', 0);
            
            if($query){
                $query->where(function($row) use($search){
                    $row->where('project_name', 'LIKE', '%'. $search. '%');
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
            
            return view('site_visits.data',compact('items'))->render();
        }


        return view('site_visits.index');
    }
    

    public function create(){

        $site_visit=SiteVisit::updateOrCreate(['is_new'=>1]);
        return $this->edit($site_visit);
    }
    
    public function show(SiteVisit $site_visit){
        return view('site_visits.show', compact('site_visit'));
    }

    

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(SiteVisit $site_visit)
    {

        return view('site_visits.create', compact('site_visit'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, SiteVisit $site_visit){
        
        $data = $request->validate([
            'project_name' => 'required',
            'ref_no' => 'required',
            'note' => '',
            'description' => '',
            'address' => 'required',
            'contact_person' => '',
            'mobile' => '',
            'visiting_date' => '',
            'next_visiting_date' => '',
            'status' => '',
        ]);
        
        $isNew = $site_visit->is_new == 1;
        
        $data['is_new'] = 0;
        $data['user_id'] = Auth::id();
        
        $site_visit->update($data);
        
        $msg = $isNew ? 'Site Visit Created !!' : 'Site Visit Updated !!';
        
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

        $unit=SiteVisit::find($id);
        $unit->delete();
    
        return response()->json(['status'=>true ,'msg'=>'SiteVisit Deleted !!']);
    }

}
