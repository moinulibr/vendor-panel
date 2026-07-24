<?php

namespace App\Http\Controllers;

use App\Models\Category;
use Illuminate\Http\Request;
use App\Utils\ProductUtil;
use DB;
class CategoryController extends Controller
{
    function __construct(ProductUtil $productUtil)
    {
        // $this->middleware('permission:categories.view|categories.create|categories.edit|categories.delete', ['only' => ['index','show']]);
        // $this->middleware('permission:categories.create', ['only' => ['create','store']]);
        // $this->middleware('permission:categories.edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:categories.delete', ['only' => ['destroy']]);
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
            
            $query = Category::latest()->where('is_new',0);
            
            if($search){
                $query->where(function($row) use($search){
                    $row->where('name', 'LIKE', '%'. $search. '%');
                });
            }
                
            if($status!=''){
                $query->where('status', $status);
            }
            
            if ($sort == 'top') {
                $query->where('is_top', 1);
            } elseif ($sort == 'menu') {
                $query->where('is_menu', 1);
            } elseif ($sort == 'home') {
                $query->where('is_home', 1);
            }
            
            $items=$query->paginate(30);
            

            return view('categories.data',compact('items'))->render();
        }

        return view('categories.index');
    }
    

    public function create(){

        $category=Category::updateOrCreate(['is_new'=>1,'name'=>null]);
        $cats = Category::where(['is_new'=>0,'parent_id'=>null])->get();
        return view('categories.create', compact('category','cats'));
    }
    
    public function show(Category $product): View
    {
        return view('categories.show',compact('product'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Category $category)
    {
        $cats = Category::where(['is_new'=>0,'parent_id'=>null])->get();
        return view('categories.create', compact('category','cats'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Category $category){
        $data = $request->validate([
            'name'      => 'required',
            'bd_name' => 'nullable',
            'status'    => 'nullable',
            'parent_id' => 'nullable',
        ]);
        
        $data['is_new'] = 0;
        
        // Upload image
        $image = $this->productUtil->FileUpload($request, 'image', 'categories');
        if ($image) {
            $data['image'] = $image;
        }
        
        // Check if category is new
        $isNew = $category->is_new == 1;
        
        $category->update($data);
        
        // Set message based on new or existing
        $msg = $isNew ? 'Category Created !!' : 'Category Updated !!';
        
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

        $cat=Category::find($id);
        deleteImage('categories',$cat->image);
        $cat->delete();
    
        return response()->json(['status'=>true ,'msg'=>'Category Deleted !!']);
    }


    public function categoryStatus(Request $request){

        $name='is_top';
        if (isset($request->is_home)) {
            $name='is_home';
        }else if (isset($request->is_menu)) {
            $name='is_menu';
        }
        

        $ids=$request->product_ids;

        $status=(request($name)==1)?1:0;

        DB::table('categories')->whereIn('id', $ids)->update([$name=>$status]);


        return response()->json(['status'=>true ,'msg'=>'Product Updated Status !!']);
    }
    
    public function getSubCategory(){
        
        $subcategories = Category::where('parent_id', request('category_id'))->get();
        return response()->json($subcategories);
        
        
        
    }



}
