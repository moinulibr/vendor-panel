<?php

namespace App\Http\Controllers;

use App\Models\Slider;
use Illuminate\Http\Request;
use App\Utils\ProductUtil;

class SliderController extends Controller
{
    public $productUtil;
    function __construct(ProductUtil $productUtil)
    {
        // $this->middleware('permission:brands.view|brands.create|brands.edit|brands.delete', ['only' => ['index','show']]);
        // $this->middleware('permission:brands.create', ['only' => ['create','store']]);
        // $this->middleware('permission:brands.edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:brands.delete', ['only' => ['destroy']]);
        $this->productUtil=$productUtil;
    }
    
    public function index(Request $request)
    {
        if ($request->ajax()) {
            
            $search=$request->q;
            $status=$request->status;
            $sort  = $request->sort;
            
            $query = Slider::where('is_new', 0);
            
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
            return view('sliders.data',compact('items'))->render();
        }

        return view('sliders.index');
    }
    

    public function create(){

        $slider=Slider::updateOrCreate(['is_new'=>1,'title'=>null]);
        return view('sliders.create', compact('slider'));
    }
    

    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Slider $slider)
    {
        return view('sliders.create', compact('slider'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Slider $slider){
        $data=request()->validate([
            'title' => 'required',
            'image' => '',
            'link' => '',
            'status' => 'required',
            'type' => 'required',
        ]);
        $data['is_new']=0;
        $image=$this->productUtil->FileUpload($request,'image','sliders');

        if($image){
            deleteImage('sliders',$slider->image);
            $data['image']=$image;
        }
        $slider->update($data);
        
        return response()->json(['status'=>true ,'msg'=>'Slider Created !!','function'=>'getData']);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {

        $slider=Slider::find($id);
        deleteImage('sliders',$slider->image);
        $slider->delete();
    
        return response()->json(['status'=>true ,'msg'=>'Slider Deleted !!']);
    }


}
