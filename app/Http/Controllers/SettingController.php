<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use App\Utils\ProductUtil;
use Illuminate\Support\Facades\Cache;

class SettingController extends Controller
{
    public $productUtil;
    function __construct(ProductUtil $productUtil)
    {
        // $this->middleware('permission:brands.view|brands.create|brands.edit|brands.delete', ['only' => ['index','show']]);
        // $this->middleware('permission:brands.create', ['only' => ['create','store']]);
        // $this->middleware('permission:brands.edit', ['only' => ['edit','update']]);
        // $this->middleware('permission:brands.delete', ['only' => ['destroy']]);
        $this->productUtil=$productUtil;
    }
    
    public function index(Request $request)
    {
        $item=Setting::updateOrCreate(['is_new'=>1]);
        return view('settings.index', compact('item'));
    }
    

    public function update(Request $request, Setting $setting){
        $data=request()->validate([
            'title' => 'required',
            'logo' => '',
            'favicon' => '',
            'email' => '',
            'phone' => '',
            'address' => '',
            'whats_app_no' => '',
            'facebook_link' => '',
            'youtube_link' => '',
            'instagram_link' => '',
            'linkedin_link' => '',
            'pinterest_link' => '',
            'tiktok_link' => '',
        ]);
        // ✅ Logo upload
        $logo = $this->productUtil->FileUpload($request, 'logo', 'settings');
        if ($logo) {
            deleteImage('settings', $setting->logo);
            $data['logo'] = $logo;
        }
    
        // ✅ Favicon upload 
        $favicon = $this->productUtil->FileUpload($request, 'favicon', 'favicon');
        if ($favicon) {
            deleteImage('favicon', $setting->favicon);
            $data['favicon'] = $favicon;
        }
        
        $setting->update($data);
        Cache::forget('info');

        Cache::rememberForever('info', function() {

            return Setting::first()->toArray();
        });
        
        return response()->json(['status'=>true ,'msg'=>'Setting updated !!']);
    }


}
