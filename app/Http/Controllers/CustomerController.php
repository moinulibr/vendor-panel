<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Contact;
use App\Models\Upazila;
use App\Models\UserAddress;
use Illuminate\Support\Facades\DB;

use App\Models\District;
use App\Utils\UserType;

class CustomerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->ajax()){
            
            $type='customer';
            $id=$request->contact_id;
            $search=$request->q;
            $status=$request->status;
            $sort  = $request->sort;
            $add_from  = $request->add_from;
            
            $query = Contact::Leftjoin('transactions AS t', 'contacts.id', '=', 't.contact_id')
                                ->select(
                                DB::raw("SUM(IF(t.type = 'sell', t.final_amount, 0)) as total_sell"),
                                DB::raw("SUM(IF(t.type = 'sell',
                                        (SELECT SUM(tp.amount) 
                                         FROM transaction_payments tp 
                                         WHERE tp.transaction_id = t.id),
                                    0)) as total_sell_paid"),
                                'contacts.*'
                            )
                            ->groupBy('contacts.id')
                            ->where('contacts.is_new',0);

                        $query->where('contacts.type',$type);
                        if($id){
                            $query->where('contacts.id',$id);
                        }
                        
                        if($add_from){
                            $query->where('contacts.add_from',$add_from);
                        }
                        
                        if ($search) {
                            $query->where(function ($row) use ($search) {
                                $row->where('contacts.name', 'LIKE', '%' . $search . '%')
                                    ->orWhere('contacts.address', 'LIKE', '%' . $search . '%')
                                    ->orWhere('contacts.email', 'LIKE', '%' . $search . '%')
                                    ->orWhere('contacts.mobile', 'LIKE', '%' . $search . '%');
                            });
                        }
                        
                        if($status!=''){
                            $query->where('contacts.status', $status);
                        }
                        
                        if ($sort == 'asc') {
                            $query->orderBy('contacts.id', 'asc');
                        } elseif ($sort == 'desc') {
                            $query->orderBy('contacts.id', 'desc');
                        } else {
                            $query->latest();
                        }
                    
            $items=$query->paginate(30);
            return view('contacts.data',compact('items'))->render();
            
        }
        
        return view('customers.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $districts=District::get();
        
        return view('customers.create', compact('districts'));
        
        
    }

    
    public function store(Request $request)
    {
        $data=$request->validate([
            'name' => 'required',
            'last_name' => '',
            'mobile' => 'required',
            'email' => '',
            'status'  => 'nullable',
            'p_upazila' => '',
            'p_district' => '',
            'p_landmark' => '',
            'address' => '',
            's_district' => '',
            's_upazila' => '',
            's_landmark' => '',
            's_address' => '',
            'phone' => '',
            'full_name' => '',
        ]);
        $data['is_new']=0;
        $data['add_from']= UserType::CUSTOMER_ADDED_FROM_ADMIN;
        $data['type']='customer';
        $data['user_id']= auth()->id();
        $address_contact=[];
        if(isset($request->same_shipping)){
            $address_contact=[
                'name'=>$data['name'].' '.$data['last_name'],
                'upazila_id'=>$data['p_upazila'],
                'district_id'=>$data['p_district'],
                'address'=>$data['address'],
                'phone'=>$data['mobile'],  
            ];
            
        }else if($data['s_district'] && $data['full_name']){
            $address_contact=[
                'name'=>$data['full_name'],
                'upazila_id'=>$data['s_upazila'],
                'district_id'=>$data['s_district'],
                'address'=>$data['s_address'],
                'phone'=>$data['phone'], 
            ]; 
        }
        
        unset($data['phone']);
        unset($data['full_name']);
        $contact = Contact::create($data);
        
        if(!empty($address_contact)){
            $address_contact['contact_id']=$contact->id;
            UserAddress::create($address_contact);
        }
        return response()->json(['status'=>true ,'msg'=>'Customer Created !!','contact'=>$contact,'function'=>'getData']);
    }
    
    
    

    /**
     * Display the specified resource.
     */
    public function show(string $id){
        $item=Contact::where('contacts.id',$id)
                    ->Leftjoin('transactions AS t', 'contacts.id', '=', 't.contact_id')
                            ->select(
                                DB::raw("COUNT(IF(t.type = 'sell', t.id, 0)) as total"),
                                DB::raw("SUM(IF(t.type = 'sell', final_amount, 0)) as total_sell"),
                               DB::raw("SUM(IF(t.type = 'sell',
                                        (SELECT SUM(tp.amount) 
                                         FROM transaction_payments tp 
                                         WHERE tp.transaction_id = t.id),
                                    0)) as total_sell_paid"),
                                'contacts.*'
                            )->first();
        
        $html=view('pos.partials.customer_details',compact('item'))->render();
        return response()->json(['html'=>$html,'customer_name'=>$item->name.' '.$item->last_name.' ('.$item->mobile.' )']);
        
    }
    
    public function getCustomerAddress(){
        
        $id=request('customer_id');
        $item=Contact::find($id);
        
        $contact_address=$item->contact_address;
        $contact_address_html=view('pos.partials.customer_address',compact('contact_address'))->render();
        return response()->json(['contact_address_html'=>$contact_address_html]);
    }
    public function getCustomerdetails(){
        $id=request('customer_id');
        $item=Contact::where('contacts.id',$id)
                    ->Leftjoin('transactions AS t', 'contacts.id', '=', 't.contact_id')
                    ->select(
                        DB::raw("COUNT(IF(t.type = 'sell', t.id, 0)) as total"),
                        
                        DB::raw("SUM(IF(t.type = 'sell', t.final_amount, 0)) as total_sell"),
                        DB::raw("SUM(IF(t.type = 'sell',
                                (SELECT SUM(tp.amount) 
                                 FROM transaction_payments tp 
                                 WHERE tp.transaction_id = t.id),
                            0)) as total_sell_paid"),
                            
                        'contacts.*'
                    )
                    ->with('contact_address')
                    ->first();
        $html=view('pos.partials.customer_details',compact('item'))->render();
        return response()->json(['html'=>$html,'contact'=>$item,'customer_name'=>$item->name.' '.$item->last_name.' ('.$item->mobile.' )']);
    }
    
    
    

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $districts=District::get();
        $contact=Contact::find($id);
        
        $upazilas=Upazila::where('district_id', $contact->p_district)->get();
        return view('customers.edit', compact('contact','districts', 'upazilas'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $contact=Contact::find($id);
        $data=$request->validate([
            'name' => 'required',
            'last_name' => '',
            'mobile' => 'required',
            'email' => '',
            'status'    => 'nullable',
            'p_upazila' => '',
            'p_district' => '',
            'p_landmark' => '',
            'address' => ''
        ]);
        $contact->update($data);
        return response()->json(['status'=>true ,'msg'=>'Customer Updated !!','function'=>'getData']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id){
        
        DB::beginTransaction();

        try {
            $contact=Contact::find($id);
            
            if ($contact->transactions->count()) {
                throw new \Exception("Can't Delete This Customer");
            }
            $contact->contact_address()->delete();
            $contact->delete();
            DB::commit();
            return response()->json(['status'=>true ,'msg'=>'Deleted Successfully !!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>false ,'msg'=>$e->getMessage()]);
        }
        
        
    }
    
    public function getCustomer(Request $request){
        
        if ($request->ajax()) {
            $search=trim($request->search);
            $type='customer';
            $add_from  = $request->add_from;
            $query = Contact::Leftjoin('transactions AS t', 'contacts.id', '=', 't.contact_id')
                                ->select(
                                DB::raw("SUM(IF(t.type = 'sell', t.final_amount, 0)) as total_sell"),
                                DB::raw("SUM(IF(t.type = 'sell',
                                        (SELECT SUM(tp.amount) 
                                         FROM transaction_payments tp 
                                         WHERE tp.transaction_id = t.id),
                                    0)) as total_sell_paid"),
                                'contacts.*'
                            )
                            ->latest()
                            ->groupBy('contacts.id')
                            ->where('contacts.is_new',0);
    
                    if ($type) {
                        $query->where('contacts.type',$type);
                    }
                    
                    if($add_from){
                        $query->where('contacts.add_from',$add_from);
                    }
                        
                    if(!empty($search)){
                        $query->where(function($row) use($search){
                            $row->where('contacts.name', 'LIKE', '%'. $search. '%')
                                ->orwhere('contacts.mobile', 'LIKE', '%'. $search. '%')
                                ->orwhere('contacts.contact_id', 'LIKE', '%'. $search. '%');
                        });
                    }
                    
            $items=$query->get();
            
            return view('pos.partials.customer_section',compact('items'))->render();
        }
    }
    
    public function getUpazila(){
        
        $subcategories = Upazila::where('district_id', request('district_id'))->get();
        return response()->json($subcategories);
    }
}
