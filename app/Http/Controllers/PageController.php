<?php

namespace App\Http\Controllers;

use App\Models\Page;
use Illuminate\Http\Request;

class PageController extends Controller
{
    public function index(Request $request)
    {
        if ($request->ajax()) {

            $search=$request->q;
            $status=$request->status;
            $sort  = $request->sort;
            
            $query = Page::where('is_new', 0);
            
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
            return view('pages.data',compact('items'))->render();
        }
        
        

        return view('pages.index');
    }
    

    public function create(){

        $charge=Page::updateOrCreate(['is_new'=>1]);
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
        $page=Page::find($id);

        $types=[

                'about-us'=>'About Us',
                'term-condition'=>'Term & Condition',
                'return-policy'=>'Return Policy',
                'refund-policy'=>'Refund Policy',
                'support-center'=>'Support Center',
                'payment-methods'=>'Payment Methods',
                'privacy-policy'=>'Privacy Policy',
                'faq'=>'FAQ',
        ];
        return view('pages.create', compact('page','types'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        $page = Page::find($id);

        $isNew = $page->is_new == 1;
    
        $data = $request->validate([
            'title' => 'required',
            'description' => '',
            'status' => '',
            'slug' => 'required',
        ]);
    
        $data['is_new'] = 0;
    
        $page->update($data);
    
        $msg = $isNew ? 'Page Created !!' : 'Page Updated !!';
    
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

        $brand=Page::find($id);
        $brand->delete();
    
        return response()->json(['status'=>true ,'msg'=>'Page Deleted !!']);
    }
    
}
