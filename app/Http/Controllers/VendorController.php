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


class VendorController extends Controller
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
            
            $query = User::role('Vendor');
            
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

            $data = $query->paginate(30);

            return view('vendors.data',compact('data'))->render();
        }

  
        return view('vendors.index');
    }
    
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(): View
    {
        $roles = Role::where('name', 'Vendor')->pluck('name', 'name')->all();

        return view('vendors.create',compact('roles'));
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
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|same:password_confirmation','password' => 'required|same:confirm_password',
            'mobile'    => 'required|unique:users,mobile',
            'roles' => 'required',
            'gender' => 'nullable',
            'dob' => 'nullable',
            'status' => 'nullable',
            
            // vendor address table fields
            'shop_name'     => 'required|string|max:255',
            'trade_license' => 'required|string|max:255',
            'fax'           => 'nullable|string|max:255',
            'website'       => 'nullable|string|max:255',
            'address'       => 'nullable|string',
            'our_mission'   => 'nullable|string',
            'our_vision'    => 'nullable|string',
        ]);
    
        $input = $request->all();
        $input['password'] = Hash::make($input['password']);

        $image=$this->productUtil->FileUpload($request,'image','users'); 

        if($image){
            $input['image']=$image;
        }

    
        $user = User::create($input);
        $user->assignRole($request->input('roles'));
        
        
        // Generate slug from shop_name
        $slug = Str::slug($request->shop_name);

        $count = VendorAddress::where('slug', 'LIKE', "{$slug}%")->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }
        
        VendorAddress::create([
            'user_id'       => $user->id,
            'email'         => $request->email,
            'phone'         => $request->mobile,
            'shop_name'     => $request->shop_name,
            'slug'          => $slug,
            'trade_license' => $request->trade_license,
            'fax'           => $request->fax,
            'website'       => $request->website,
            'address'       => $request->address,
            'our_mission'   => $request->our_mission,
            'our_vision'    => $request->our_vision,
        ]);
    
        return redirect()->route('vendors.index')
                        ->with('success','User created successfully');
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

        $vendor = User::with(['vendorAddress', 'roles'])->findOrFail($id);
        return view('vendors.show',compact('user','vendor'));


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
        $vendorAddress = VendorAddress::where('user_id', $user->id)->first();
        $roles = Role::where('name', 'Vendor')->pluck('name', 'name')->all();
        $userRole = $user->roles->pluck('name','name')->all();
    
        return view('vendors.edit',compact('user','roles','userRole','vendorAddress'));
    }
    
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id){
        
        $user = User::find($id);
    
        $input = $this->validate($request, [
            'name' => 'required|string|max:255',
            'last_name' => 'nullable|string|max:255',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'nullable|confirmed',
            'mobile' => 'required|unique:users,mobile,' . $id,
            'roles' => 'required',
            'gender' => 'nullable',
            'dob' => 'nullable',
            'status' => 'nullable',
    
            // Vendor Address fields
            'shop_name' => 'required|string|max:255',
            'trade_license' => 'required|string|max:255',
            'fax' => 'nullable|string|max:255',
            'website' => 'nullable|string|max:255',
            'address' => 'nullable|string',
            'our_mission'   => 'nullable|string',
            'our_vision'    => 'nullable|string',
        ]);
    
        // Hash password if provided
        if(!empty($input['password'])){ 
            $input['password'] = Hash::make($input['password']);
        } else {
            $input = Arr::except($input, ['password']);    
        }
    
        // File upload if image provided
        $image = $this->productUtil->FileUpload($request, 'image', 'users'); 
        if($image){
            deleteImage('users', $user->image);
            $input['image'] = $image;
        }
    
        // Update user
        $user->update($input);
    
        // Update roles
        if (isset($request->roles)) {
            DB::table('model_has_roles')->where('model_id', $id)->delete();
            $user->assignRole($request->input('roles'));
        }
        
        // Generate slug from shop_name
        $slug = Str::slug($request->shop_name);

        $count = VendorAddress::where('slug', 'LIKE', "{$slug}%")->count();
        if ($count > 0) {
            $slug = $slug . '-' . ($count + 1);
        }
    
        // Update or create VendorAddress
        $vendorAddressData = [
            'email'         => $request->email,
            'phone'         => $request->mobile,
            'shop_name'     => $request->shop_name,
            'slug'          => $slug,
            'trade_license' => $request->trade_license,
            'fax'           => $request->fax,
            'website'       => $request->website,
            'address'       => $request->address,
            'our_mission'   => $request->our_mission,
            'our_vision'    => $request->our_vision,
        ];
    
        VendorAddress::updateOrCreate(
            ['user_id' => $user->id], // match by user_id
            $vendorAddressData        // update or create with these fields
        );
    
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
        return redirect()->route('vendors.index')
                        ->with('success','User deleted successfully');
    }
}