<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\VendorOrder;
use App\Models\Transaction;

class VendorOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            
            $shipping_status=$request->shipping_status;
            $payment_status=$request->payment_status;
            $search=$request->q;
            $date=$request->date;
            
            $query = VendorOrder::latest();
            
            if($search){
                $query->where(function($row) use($search){
                    $row->where('invoice_no', 'LIKE', '%'. $search. '%');
                });
            }
             
            
            if($shipping_status){
                $query->where('shipping_status', $shipping_status);
            }
            
            if($payment_status){
                $query->where('payment_status', $payment_status);
            }
            
            if($date){
                
                $dates  = explode('~', $date);
                
                $start = isset($dates[0]) ? trim($dates[0]) : null;
                $end   = isset($dates[1]) ? trim($dates[1]) : null;
                $query->whereDate('created_at', '>=', $start)->whereDate('created_at', '<=', $end);
                
            }
            

            $items=$query->paginate(30);

            return view('vendor_orders.data',compact('items'))->render();
        }

        return view('vendor_orders.index');

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
        $order=VendorOrder::find($id);

        return view('vendor_orders.show', compact('order'));

    }
    
    public function orderPrint(string $id)
    {
        $order=VendorOrder::find($id);

        return view('vendor_orders.print', compact('order'));

    }

    public function vendorOrderStatus($id){

        $order=VendorOrder::find($id);
        $statuses=getStatusList();
        return view('vendor_orders.edit_status', compact('order','statuses'));

    }

    public function updateOrderStatus($id){

        $order=VendorOrder::find($id);
        $data = request()->except('_token');
        $order->update($data);
        
        if($data['shipping_status']=='delivered'){
            
            $transaction_id=$order->trnsaction_id;
            $check=VendorOrder::where('shipping_status','!=','delivered')->count();
            
            if($check==0){
                $transaction=Transaction::find($transaction_id);
                $transaction->shipping_status='delivered';
                $transaction->save();
            }
            
        }
        
        
        return response()->json(['status'=>true ,'msg'=>'Order Status Updated !!','function'=>'ggg']);

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
        $order=VendorOrder::find($id);

        foreach ($order->lines as $key => $line) {
            // $this->productUtil->decreaseProductStock($line->product_id,$line->product_id, $order->location_id,$line->quantity);
            $line->delete();
        }
        $order->delete();
        return response()->json(['status'=>true ,'msg'=>'Order Deleted Successfully !!']);

    }
}
