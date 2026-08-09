<?php

namespace App\Http\Controllers;

use App\Models\Contact;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class SupplierController extends Controller
{

    public function index(Request $request)
    {
        if ($request->ajax()) {

            $type='supplier';
            $id=$request->contact_id;
            $search=$request->q;
            $status=$request->status;
            $sort  = $request->sort;
            $vendor_id  = $request->vendor_id;
            
            $query = Contact::Leftjoin('transactions AS t', 'contacts.id', '=', 't.contact_id')
                                ->select(
                                
                                
                                DB::raw("SUM(IF(t.type = 'purchase', t.final_amount, 0)) as total_purchase"),
                                DB::raw("SUM(IF(t.type = 'purchase',
                                        (SELECT SUM(tp.amount) 
                                         FROM transaction_payments tp 
                                         WHERE tp.transaction_id = t.id),
                                    0)) as total_purchase_paid"),
                                    
                                
                                'contacts.*'
                            )
                            ->groupBy('contacts.id')
                            ->where('contacts.is_new',0);

                   
                        $query->where('contacts.type',$type);
                        
                        if($id){
                            $query->where('contacts.id',$id);
                        }
                        
                        if($vendor_id){
                            $query->where('contacts.user_id',$vendor_id);
                        }
                        
                        if($query){
                            $query->where(function($row) use($search){
                                $row->where('contacts.name', 'LIKE', '%'. $search. '%');
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

            return view('suppliers.data',compact('items'))->render();
        }
        
        
        $query_user = User::role(['Vendor','Admin']);
                    if(getRole()=='Vendor'){
                        $query_user->whereId(Auth::id());
                    }
        $users=$query_user->get();
        
        return view('suppliers.index', compact('users'));
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
        $contact = Contact::Leftjoin('transactions AS t', 'contacts.id', '=', 't.contact_id')
                        ->leftJoin('users', 'contacts.user_id', '=', 'users.id')
                        ->select(
                       DB::raw("SUM(IF(t.type = 'purchase', t.final_amount, 0)) as total_purchase"),
                                DB::raw("SUM(IF(t.type = 'purchase',
                                        (SELECT SUM(tp.amount) 
                                         FROM transaction_payments tp 
                                         WHERE tp.transaction_id = t.id),
                                    0)) as total_purchase_paid"),
                        'contacts.*',
                        'users.name as user_name',
                        'users.email as user_email',
                        'users.mobile as user_mobile'
                    )
                    ->latest()
                    ->groupBy('contacts.id')
                    ->where('contacts.id', $id)
                    ->first();

        return view('suppliers.show', compact('contact'));  
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
