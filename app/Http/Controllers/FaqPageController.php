<?php

namespace App\Http\Controllers;

use App\Models\FaqPage;
use Illuminate\Http\Request;

class FaqPageController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {
            
            $search=$request->q;
            
            $query = FaqPage::latest()->where('is_new',0);
            
            if($query){
                $query->where(function($row) use($search){
                    $row->where('title', 'LIKE', '%'. $search. '%');
                });
            }
            
            $items=$query->paginate(30);

            return view('faq_pages.data',compact('items'))->render();
        }

        return view('faq_pages.index');
    }
    

    public function create(){

        $charge=FaqPage::updateOrCreate(['is_new'=>1]);
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
        $item=FaqPage::find($id);

        return view('faq_pages.create', compact('item'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $faq = FaqPage::findOrFail($id);

        $isNew = $faq->is_new == 1;
    
        $data = $request->validate([
            'title'       => 'required',
            'status'      => 'nullable',
            'description' => 'nullable',
        ]);
    
        $data['is_new'] = 0;
    
        $faq->update($data);
    
        $msg = $isNew ? 'FaqPage Created !!' : 'FaqPage Updated !!';
    
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

        $brand=FaqPage::find($id);
        $brand->delete();
    
        return response()->json(['status'=>true ,'msg'=>'FaqPage Deleted !!']);
    }
    
}
