<?php
namespace App\Utils;
use App\Utils\Util;
use Intervention\Image\Laravel\Facades\Image;
use App\Models\ProductStock;
use App\Models\Transaction;
use App\Models\Product;

class ProductUtil extends Util
{
    
    public function FileUpload($request , $file_name, $folder){

    	$fileName='';

        if($request->hasFile($file_name)) {
            $image = $request->file($file_name);
            $fileName = time().'.'.$image->extension();
           
            // $destinationPathThumbnail = public_path('/thumbnail');
            // $img = Image::read($image->path());
            // $img->resize(100, 100, function ($constraint) {
            //     $constraint->aspectRatio();
            // })->save($destinationPathThumbnail.'/'.$fileName);
         
            $destinationPath = public_path('/'.$folder);
            $image->move($destinationPath, $fileName);
        }

    	return $fileName;

    }
    public function FileUploadWithSize($request, $file_name, $folder)
    {
        $fileData = [
            'name' => '',
            'size' => null
        ];

        if ($request->hasFile($file_name)) {
            $image = $request->file($file_name);
            $imageSize = $image->getSize();
            $fileName = time() . '.' . $image->extension();
            $destinationPath = public_path('/' . $folder);
            $image->move($destinationPath, $fileName);

            $fileData['name'] = $fileName;
            $fileData['size'] = $imageSize; //image size
        }

        return $fileData;
    }



    public function increaseProductStock($product_id,$variation_id,$location_id, $stock){

        
        
        $stock_manage=Product::find($product_id)->stock_manage;
        
        if($stock_manage){
            $item=ProductStock::where(['product_id'=>$product_id,'variation_id'=>$variation_id,'location_id'=>$location_id])->first();
            if ($item) {
            
            }else{
                $item=new ProductStock();
                $item->product_id=$product_id;
                $item->variation_id=$variation_id;
                $item->location_id=$location_id;
                $item->qty_available=0;
            }
    
            $item->qty_available+=$stock;
            $item->save();
        }
        


        return true;

    }


    public function updateProductStock($product_id, $variation_id,$location_id, $new_stock,$old_stock){
        $stock_manage=Product::find($product_id)->stock_manage;
        
        if($stock_manage){
            
            $item=ProductStock::where(['product_id'=>$product_id, 'variation_id'=>$variation_id])->first();
            $stock=$new_stock -$old_stock;
            if ($stock !=0) {
                if ($item) {
                    
                }else{
                    $item=new ProductStock();
                    $item->product_id=$product_id;
                    $item->variation_id=$variation_id;
                    $item->location_id=$location_id;
                    $item->qty_available=0;
                }
    
                $item->qty_available +=$stock;
                $item->save();
    
            }
        
            
        }
        
        return true;

        

    }


    public function decreaseProductStock($product_id, $variation_id, $location_id,$stock){
        $stock_manage=Product::find($product_id)->stock_manage;
        
        if($stock_manage){
            
            $item=ProductStock::where(['product_id'=>$product_id, 'variation_id'=>$variation_id, 'location_id'=>$location_id])->first();

            if($item){
                $item->qty_available-=$stock;
                $item->save();
            }
        }
        
        
        return true;


    }


    public function checkProductStock($product_id, $variation_id, $location_id){
        
        $item=ProductStock::where([
                                    'product_id'=>$product_id, 
                                    'variation_id'=>$variation_id,
                                    'location_id'=>$location_id,
                                ])->first();
        return $item?$item->qty_available:0;
    }
    
    public function generateInvoiceNumber(){
        $year = date('Y');
    
        $lastInvoice = Transaction::where(['is_new'=>0,'type'=>'sell','is_pos'=>1])->whereNotNull('invoice_no')->latest()->first();
    
        if (!$lastInvoice) {
            $number = 1;
        } else {
            // Extract last 5 digits
            $number = intval(substr($lastInvoice->invoice_no, -5)) + 1;
        }
    
        return 'INV-' . $year . '-' . str_pad($number, 5, '0', STR_PAD_LEFT);
    }

}

