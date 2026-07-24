<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Variation;
use App\Utils\ProductUtil;
use Illuminate\Support\Facades\DB;


class BarcodeController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('barcodes.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        $search= $request->get('search');
        $query=DB::table('variations as v')
                        ->join('products as p','p.id','v.product_id')
                        ->where(function($row) use($search){
                            
                            $row->where('p.name', 'LIKE', '%'. $search. '%')
                                ->orwhere('p.sku', 'LIKE', '%'. $search. '%')
                                ->orwhere('v.sub_sku', 'LIKE', '%'. $search. '%');
                        })
                        ->select('p.name as text','p.sku','p.image','p.created_at','v.id','v.sub_sku','p.type','v.purchase_price','v.sell_price',
                        
                        DB::raw("CASE 
                                WHEN p.type = 'variable' 
                                THEN CONCAT(p.name, ' - ', v.name)
                                ELSE p.name
                            END AS value
                        "));

        $data = $query->latest()->get();

        return response()->json($data); 
        
    }
    
    public function barcodeProductEntry(Request $request){

        $id=$request->id;
        $item=Variation::with('product')->find($id);

        if ($item) {
            $html=view('barcodes.product_row', compact('item'))->render();

            return response()->json(['success'=>true,'html'=>$html]);
        }else{
            return response()->json(['success'=>false,'msg'=>'Product Note Found !!']);
        }
    }
    

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        
        $ids=$request->variation_id;

        $quantities = $request->quantity;
        $show_business = isset($request->show_business)?1:null;
        $show_price = isset($request->show_price)?1:null;
        $show_name = isset($request->show_name)?1:null;
        
        $items = [];
    
        foreach ($ids as $index => $id) {
            $item = Variation::find($id);
            $qty = $quantities[$index];
    
            // Add the product to the queue X number of times
            for ($i = 0; $i < $qty; $i++) {
                $items[] = [
                    'business_name' => $show_business ?getInfo('title'):'',
                    'name' => $show_name?$item->product->name:'',
                    'barcode' => $item->product->sku, // e.g., '12345678'
                    'price' => $show_price?$item->product->sell_price:''
                ];
            }
        }

        
        return view('barcodes.modal', compact('items'))->render();
            
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
