<?php

namespace App\Http\Controllers;

use App\Models\Product;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Brand;
use App\Models\User;
use App\Models\Unit;
use App\Models\VariantAttribute;
use App\Models\Variation;
use App\Models\Discount;
use Illuminate\Http\Request;
use App\Utils\ProductUtil;
use DB;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Str;
use App\Exports\ProductsExport;
use App\Imports\ProductsImport;
use Maatwebsite\Excel\Facades\Excel;
use ZipArchive;
use Illuminate\Support\Facades\File;
use Maatwebsite\Excel\Excel as ExcelFormat;

class ProductController extends Controller
{
    function __construct(ProductUtil $productUtil)
    {
        // $this->middleware('permission:products.view|products.create|products.edit|products.delete', ['only' => ['index','show']]);
        // $this->middleware('permission:products.create', ['only' => ['create','store']]);
        // $this->middleware('permission:products.edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:products.delete', ['only' => ['destroy']]);
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
            
            $user_id=$request->user_id;
            $category_id=$request->category_id;
            $brand_id=$request->brand_id;
            $type=$request->type;
            $stock_manage=$request->stock_manage;
            $search=$request->q;
            $status=$request->status;
            $discount_id=$request->discount_id;
            $ecom_status=$request->ecom_status;
            
            $query = Product::leftjoin('product_stocks as ps' ,'ps.product_id','products.id')
                    ->latest()->where('products.is_new',0)
                    ->select('products.*',DB::raw("SUM(ps.qty_available) AS stock"));
            
                if($search){
                    $query->where(function($row) use($search){
                        $row->where('products.name', 'LIKE', '%'. $search. '%');
                        $row->orwhere('products.sku', 'LIKE', '%'. $search. '%');
                    });
                }
                
                if($discount_id){
                    $discount=Discount::find($discount_id);
                    $query->where(function($row) use($discount){
                        $row->where('products.category_id', $discount->category_id);
                        $row->orwhere('products.brand_id', $discount->brand_id);
                        $row->orwhere('products.user_id', $discount->user_id);
                    });
                }
                
                
                if($status!=''){
                    $query->where('status', $status);
                }
                
                if($category_id){
                    $query->where('products.category_id',$category_id);
                }
                
                if($user_id){
                    $query->where('products.user_id',$user_id);
                }
                
                if($brand_id){
                    $query->where('products.brand_id',$brand_id);
                }
                
                if($type){
                    $query->where('products.type',$type);
                }
                
                if($ecom_status){
                    $query->where($ecom_status,1);
                }
                
                if($stock_manage){
                    
                    
                    if($stock_manage==1){
                        $query->where('products.stock_manage', 1);
                    }else{
                        $query->whereNull('products.stock_manage');
                    }
                    
                }
                
                
            $items =$query->groupBy('products.id')->paginate(30);

            return view('products.data',compact('items'))->render();
        }
        
        $data['cats'] = Category::where(['is_new'=>0,'parent_id'=>null])->get();
        $data['users'] = User::role('Vendor')->get();
        $data['brands'] = Brand::whereIsNew(0)->get();
        $data['discounts']=Discount::where(['is_new'=>0])->get();
        return view('products.index', $data);
    }
    

    public function create(){

        $product=Product::updateOrCreate(['is_new'=>1,'name'=>null]);

        return $this->edit($product);

        
    }
    
    public function show(Product $product){
        
        return view('products.show',compact('product'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function edit(Product $product)
    {
        
        $cats = Category::where(['is_new'=>0,'parent_id'=>null])->get();
        $brands = Brand::whereIsNew(0)->get();
        $units = Unit::whereIsNew(0)->get();
        $sub_cats = [];
        $variants = VariantAttribute::where(['is_new'=>0,'status'=>1])->get();
        if($product->category_id){
            $sub_cats = Category::where(['is_new'=>0,'parent_id'=>$product->category_id])->get();;
        }

        $array=[];

        
        if ($product->variants) {
            $array=json_decode($product->variants,true);
        }
        $newarr=[];
        foreach ($array as $key => $arrayn) {
            foreach ($arrayn as $key => $var) {
                
                foreach ($var as $key => $val) {
                    $newarr[]=$val;
                }

            }
        }


        $query_user = User::role(['Vendor','Admin']);
                    if(getRole()=='Vendor'){
                        $query_user->whereId(Auth::id());
                    }
        $users=$query_user->get();
        return view('products.create', compact('users','newarr','product','cats', 'brands','units','sub_cats', 'variants'));
    }

    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product){
        
        $isNew = $product->is_new == 1;
        
        $data=request()->validate([
            'name' => 'required',
            'name_bangla' => '',
            'category_id' => 'required',
            'user_id' => 'required',
            'sub_category_id' => '',
            'status' => '',
            'sku' => '',
            'type' => '',
            'description' => '',
            'stock_alert' => '',
            'brand_id' => '',
            'unit_id' => '',
            'type' => '',
            'is_ecom' => '',
            'is_feature' => '',
            'is_reco' => '',
            'purchase_price' => 'required',
            'sell_price' => 'required',
            'wholesale_price' => '',
            'variants' => '',
            'warranty_note' => '',
            'return_note' => '',
            'specification' => '',
            'return_days' => '',
            'warranty_days' => '',
            'estimate_delivery_day' => '',
        ]);
        
        if (!empty($request->video_link) && $request->video_link !== $product->video_link) {

            $videoId = $this->getYoutubeId($request->video_link);
        
            $data['video_link'] = $videoId;
        }

        $data['stock_manage']=isset($request->stock_manage)?1:null;
        $data['warranty_available']=isset($request->warranty_available)?1:null;
        $data['return_available']=isset($request->return_available)?1:null;
        if($product->is_new==1){
            $data['is_new']=0;
        }
        
        if ($data['type']=='variable') {
            $variations=$request->variations;
            $data['is_new']=0;
        }else{
            $variations[]=[
                    'name'=>'dummy',
                    'sub_sku'=> $data['sku'].'-1',
                    'purchase_price'=> $data['purchase_price'],
                    'sell_price'=> $data['sell_price'],
            ];
        }

        //$image=$this->productUtil->FileUpload($request,'image','products'); 
        $imageData = $this->productUtil->FileUploadWithSize($request, 'image', 'products');

        if ($imageData['name']) {
            deleteImage('products', $product->image);
            $data['image'] = $imageData['name'];
            $data['image_size'] = $imageData['size']; //image size processing
        }
        //if($image){
            //deleteImage('products',$product->image);
            //$data['image']=$image;
        //}

        $slug = Str::slug($request->name);
        $count = Product::where('slug', 'LIKE', "{$slug}%")
                        ->where('id', '!=', $product->id)
                        ->count();
        
        $data['slug'] = $count ? "{$slug}-{$count}" : $slug;

        $product->update($data);

        if ($request->hasFile('images')) {
            $this->saveGalleryImages($request, $product);
            /*foreach ($request->file('images') as $image) {
                $imageName = time().'_'.uniqid().'.'.$image->getClientOriginalExtension();
                $image->move(public_path('/products'), $imageName);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image' =>  $imageName,
                    'image_size' =>  $image->getSize(), //image size processing
                ]);
            }*/
        }

        $ids=[];
        foreach ($variations as $key => $variation) {

            $vname=$variation['name'];
            unset($variation['name']);
            $item=Variation::updateOrCreate(['product_id'=>$product->id,'name'=>$vname],$variation);
            $ids[] = $item->id;
        }
        $product->variations()->whereNotIn('id', $ids)->delete();
        
        $msg = $isNew ? 'Product Created !!' : 'Product Updated !!';
        return response()->json([
            'status' => true,
            'msg' => $msg,
            'function' => 'getData'
        ]);
        // return response()->json(['status'=>true ,'msg'=>'product Created !!','function'=>'getData']);
    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy($id){

        DB::beginTransaction();
        $product = Product::find($id);
        try {
            if ($product->stocks->count() || $product->sell_lines->count()) {
                throw new \Exception("Can't Delete This Product");
            }
            
            foreach($product->images() as $img){
                deleteImage('products',$img->image);
                $img->delelte();
            }
            deleteImage('products',$product->image);
            $product->variations()->delete();
            $product->delete();
            
            DB::commit();
            return response()->json(['status' => true, 'msg' => 'Product Is Deleted !!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, 'msg' => $e->getMessage()]);
        }
        
    }
    
    public function multiImageDelete($id){

        DB::beginTransaction();
        $product_image = ProductImage::find($id);
        try {
            
            deleteImage('products',$product_image->image);
            $product_image->delete();
            
            DB::commit();
            return response()->json(['status' => true, 'msg' => 'Image Is Deleted !!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status' => false, 'msg' => $e->getMessage()]);
        }
        
    }

    public function productUpdate(Request $request){

        $name='is_ecom';
        if (isset($request->is_reco)) {
            $name='is_reco';
        }else if (isset($request->is_feature)) {
            $name='is_feature';
        }
        

        $ids=$request->product_ids;

        $status=(request($name)==1)?1:0;

        DB::table('products')->whereIn('id', $ids)->update([$name=>$status]);


        return response()->json(['status'=>true ,'msg'=>'Product Updated Status !!']);
    }
    
    private function getYoutubeId($url){
        preg_match(
            '%(?:youtube\.com/(?:[^/]+/.+/|(?:v|e(?:mbed)?)/|.*[?&]v=)|youtu\.be/)([^"&?/ ]{11})%i',
            $url,
            $match
        );
    
        return $match[1] ?? null;
    }


    private function saveGalleryImages($request, $product)
    {
        if ($request->hasFile('images')) {
            foreach ($request->file('images') as $image) {
                $imgSize = $image->getSize();
                $imageName = time() . '_' . uniqid() . '.' . $image->getClientOriginalExtension();
                $image->move(public_path('/products'), $imageName);

                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => $imageName,
                    'image_size' => $imgSize //image size
                ]);
            }
        }
        return true;
    }



    public function exportExcel()
    {
        return Excel::download(new ProductsExport, 'bulk_product_import.xlsx');
    }

    public function importExcel(Request $request)
    {
        set_time_limit(600); // ১০ মিনিট
        ini_set('memory_limit', '512M');
        
        $request->validate([
            'excel_file' => 'required|mimes:xlsx,csv,xls',
            'zip_file'   => 'nullable|mimes:zip',
        ]);

        $unzippedPath = null;

        if ($request->hasFile('zip_file')) {
            $zip = new \ZipArchive;
            $zipFile = $request->file('zip_file');
            $unzippedPath = storage_path('app/temp_product_images/' . time());

            if ($zip->open($zipFile->getRealPath()) === TRUE) {
                $zip->extractTo($unzippedPath);
                $zip->close();
            }
        }

        try {
            $file = $request->file('excel_file');

            // ফাইল এক্সটেনশন অনুযায়ী Format সেট করা
            $extension = strtolower($file->getClientOriginalExtension());
            $format = ExcelFormat::XLSX; // Default XLSX

            if ($extension === 'csv') {
                $format = ExcelFormat::CSV;
            } elseif ($extension === 'xls') {
                $format = ExcelFormat::XLS;
            }

            // ৩ নম্বর প্যারামিটারে $format পাস করে দেয়া হলো
            Excel::import(new ProductsImport($unzippedPath), $file, null, $format);

            if ($unzippedPath && File::exists($unzippedPath)) {
                File::deleteDirectory($unzippedPath);
            }

            return response()->json([
                'status' => true,
                'msg' => 'Products Imported Successfully!',
                'function' => 'getData'
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'status' => false,
                'msg' => 'Import Error: ' . $e->getMessage()
            ], 500);
        }
    }

    // Product Created History
    public function createdHistory(Request $request)
    {
        $status = 1;
        $productHistory = Product::select(
            DB::raw('DATE(created_at) as date'),
            DB::raw('count(*) as total')
        )
        ->where('status', $status)
        ->groupBy('date')
        ->orderBy('date', 'desc')
        ->get();
        $totalCreatedProducts = Product::select('stuatus')->where('status', $status)->count();
        return view('products.createdHistory', compact('productHistory', 'totalCreatedProducts'))->render();
    }

}
