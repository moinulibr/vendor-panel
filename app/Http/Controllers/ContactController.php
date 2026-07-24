<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\UserAddress;
use App\Models\ContactNextPayment;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\Upazila;
use App\Utils\ProductUtil;
use App\Models\District;
use App\Models\User;

class ContactController extends Controller
{
    function __construct(ProductUtil $productUtil)
    {
        // $this->middleware('permission:locations.view|locations.create|locations.edit|locations.delete', ['only' => ['index','show']]);
        // $this->middleware('permission:locations.create', ['only' => ['create','store']]);
        // $this->middleware('permission:locations.edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:locations.delete', ['only' => ['destroy']]);
        $this->productUtil=$productUtil;
    }
    
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        

    }
    
    public function getContactDue($id){
        
        $item = Contact::Leftjoin('transactions AS t', 'contacts.id', '=', 't.contact_id')
                        ->select(
                        DB::raw("SUM(IF(t.type != '', t.final_amount, 0)) as total_sell"),
                        DB::raw("SUM(IF(t.type != '',
                                (SELECT SUM(tp.amount) 
                                 FROM transaction_payments tp 
                                 WHERE tp.transaction_id = t.id),
                            0)) as total_sell_paid"),
                        
                        
                            
                        
                        'contacts.*'
                    )
                    ->latest()
                    ->groupBy('contacts.id')
                    ->where('contacts.id', $id)
                    ->first();
        return view('transactions.contact_due_payment', compact('item')); 
        
        
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(Request $request)
    {
        
        $query_user = User::role(['Vendor']);
                    if(getRole()=='Vendor'){
                        $query_user->whereId(Auth::id());
                    }
        $users = $query_user->get();
        
        $type=$request->type;
        $contact=Contact::updateOrCreate(['is_new'=>1,'type'=>$type]);
        $districts=District::get();
        
        return view('contacts.create', compact('contact','type','districts','users'));

    }

    /**
     * Store a newly created resource in storage.
     */
    
    
    public function storeCustomerAddress(Request $request){
        
   
        
        $data=$request->validate([
            'contact_id' => 'required',
            'name' => 'required',
            'phone' => 'required',
            'district_id' => 'required',
            'upazila_id' => '',
            'address' => 'required',
        ]);
        
        UserAddress::create($data);
        return response()->json(['status'=>true ,'msg'=>'Shipping Address Created !!']);
        
    }
    

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        $contact = Contact::Leftjoin('transactions AS t', 'contacts.id', '=', 't.contact_id')
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
                    ->where('contacts.id', $id)
                    ->first();
        return view('contacts.show', compact('contact'));    
                            
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Contact $contact)
    {
        $type=$contact->type;
        $districts=District::get();
        $upazilas=Upazila::where('district_id', $contact->p_district)->get();
        
        $query_user = User::role(['Vendor']);
                    if(getRole()=='Vendor'){
                        $query_user->whereId(Auth::id());
                    }
        $users = $query_user->get();

        return view('contacts.create', compact('contact','type','upazilas','districts','users'));

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Contact $contact){

        $isNew = $contact->is_new == 1;
    
        $data = $request->validate([
            'name'        => 'required',
            'mobile'      => 'required',
            'email'       => '',
            'address'     => 'required',
            'status'      => 'nullable',
            'p_upazila'   => '',
            'p_district'  => '',
            'p_landmark'  => '',
            'user_id'  => 'required',
        ]);
    
        $data['is_new'] = 0;
    
        $image = $this->productUtil->FileUpload($request, 'image', 'contacts');
        if ($image) {
            $data['image'] = $image;
        }
    
        $contact->update($data);
    
        // Address table insert
        UserAddress::updateOrCreate(
            ['contact_id' => $contact->id],
            [
                'name'        => $data['name'],
                'upazila_id'  => $data['p_upazila'] ?? null,
                'district_id' => $data['p_district'] ?? null,
                'address'     => $data['address'],
                'phone'       => $data['mobile'],
            ]
        );
    
        $msg = $isNew ? 'Contact Created !!' : 'Contact Updated !!';
    
        return response()->json([
            'status' => true,
            'msg' => $msg,
            'function' => 'getData'
        ]);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id){
        
        DB::beginTransaction();

        try {
            $contact=Contact::find($id);
            
            if ($contact->transactions->count()) {
                throw new \Exception("Can't Delete This Supplier");
            }
            $contact->delete();
            DB::commit();
            return response()->json(['status'=>true ,'msg'=>'Deleted Successfully !!']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json(['status'=>false ,'msg'=>$e->getMessage()]);
        }

    }
    
    public function nextPaymentEdit($id){
        
        $item=ContactNextPayment::find($id);
        return view('contacts.nextPaymentEdit', compact('item'));
        
    }
    
    public function nextPaymentUpdate($id){
        
        $item=ContactNextPayment::find($id);
        $item->note=request('note');
        $item->next_payment_date=request('next_payment_date');
        $item->save();
        return response()->json(['status'=>true ,'msg'=>'Note Update Successfully !!']);
        
    }
}
