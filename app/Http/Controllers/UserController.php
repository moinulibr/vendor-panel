<?php
    
namespace App\Http\Controllers;
    
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\VendorAddress;
use Spatie\Permission\Models\Role;
use DB;
use Hash;
use Illuminate\Support\Arr;
use Illuminate\View\View;
use Illuminate\Http\RedirectResponse;
use App\Utils\ProductUtil;
use Illuminate\Support\Facades\Auth;


class UserController extends Controller
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
            
            $search=$request->q;
            $status=$request->status;
            $sort  = $request->sort;
            
            $query = User::whereHas('roles', function ($q) {
                        $q->where('name', '!=', 'Vendor');
                    });
                    
            if($search){
                $query->where(function($row) use($search){
                    $row->where('name', 'LIKE', '%'. $search. '%');
                    $row->orwhere('email', 'LIKE', '%'. $search. '%');
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
            
            $data=$query->paginate(30);

            return view('users.data',compact('data'))->render();
        }

  
        return view('users.index');
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $roles = Role::where('name','!=', 'Vendor')->pluck('name', 'name')->all();

        return view('users.create',compact('roles'));
    }
    
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request): RedirectResponse
    {
        $this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:confirm-password',
            'roles' => 'required'
        ]);
    
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $image=$this->productUtil->FileUpload($request,'image','users'); 

        if($image){
            $input['image']=$image;
        }

    
        $user = User::create($input);
        $user->assignRole($request->input('roles'));
        
        return response()->json(['status'=>true ,'msg'=>'User created successfully !!','function'=>'getData']);
    }
    
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id): View
    {
        $user = auth()->user();

        $vendor=$user->vendor_address;
        return view('users.show',compact('user','vendor'));
    }
    
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = User::find($id);
        $roles = Role::where('name','!=', 'Vendor')->pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
    
        return view('users.edit',compact('user','roles','userRole'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $user = User::find($id);
        $input=$this->validate($request, [
            'name' => 'required',
            'email' => 'required|email|unique:users,email,'.$id,
            // 'mobile' => 'required|unique:users,mobile,'.$id,
            'password' => '',
            'gender' => '',
            'status' => '',
            'dob' => '',
        ]);
    
     
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        }else{
            $input = Arr::except($input,array('password'));    
        }

        $image=$this->productUtil->FileUpload($request,'image','users'); 

        if($image){
            deleteImage('users', $user->image);
            $input['image']=$image;
        }

        
        $user->update($input);
        if (isset($request->roles)) {
            DB::table('model_has_roles')->where('model_id',$id)->delete();
            $user->assignRole($request->input('roles'));
        }
        
        return response()->json(['status'=>true ,'msg'=>'Profile Updated !!','function'=>'getData']);
    }

    public function vandorUpdate(Request $request){

        $user = auth()->user();
        $input=$this->validate($request, [
            'name' => 'required',
            'email' => 'required|email',
            'phone' => 'required',
            'address' => 'required',
            'return' => '',
            'warranty' => '',
            'trade_license' => '',
            'website' => '',
            'fax' => '',
        ]);

        $result=VendorAddress::updateOrCreate(['user_id'=>$user->id],$input);

        return response()->json(['status'=>true ,'msg'=>'Vendor Profile Updated !!']);


    }
    
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id): RedirectResponse
    {
        User::find($id)->delete();
        return redirect()->route('users.index')
                        ->with('success','User deleted successfully');
    }
}