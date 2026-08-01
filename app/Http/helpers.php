<?php
use App\Models\Discount;
use App\Utils\UserType;

function sendingOptToMobile(){
	return true;
}

function activeMeny($segment){
    
    return request()->segment(1)==$segment ?'active':'';
    
}

function activeMeny2($segment){
    
    return request()->segment(2)==$segment ?'active':'';
    
}


function getInfo($key){

    $item=\Cache::get('info');

    return $item[$key]??'';
}

function dateFormate($date=null){
	$value='';
	if ($date) {
		$value=date('d M, Y', strtotime($date));
	}
	return $value;
}

function newdate($date=null){
	$value=null;
	if ($date) {
		$value=date('Y-m-d', strtotime($date));
	}
	return $value;
}


function getImage($folder=null,$value=null){
    
	$url = asset('images/no_found.png');
	$path = public_path($folder.'/'.$value);
	if (!empty($folder) && (!empty($value))) {
		if(file_exists($path)){
			$url = asset($folder.'/'.$value);
		}
	}
	return $url;
}

function deleteImage($folder=null, $file=null){

    if (!empty($folder) && !empty($file)) {
        $path = public_path($folder.'/'.$file);
        $isExists = file_exists($path);
        if ($isExists) {
            unlink($path);
        }
    }
    return true;
}


function priceFormate($amount=0){
    
    return '৳ '.number_format($amount,0);
    
}

function getRole(){

	return auth()->user()->roles->pluck('name')[0] ??'';
}

function getOrderStatus($status){

	$array=[
		'pending'=>'New Order',
		'on_hold'=>'On Hold',
		
		'processing'=>'Processing',
		'shipped'=>'Shipped',
		'delivered'=>'Delivered',
		'partial_delivered'=>'Partial Delivered',
		'cancelled'=>'Cancelled',
		
	];
	return $array[$status]??'';
}

function getStatusList(){

	$array=[
		'pending'=>'Pending',
		'on_hold'=>'On Hold',
		'processing'=>'Processing',
		'shipped'=>'Shipped',
		'delivered'=>'Delivered',
		'partial_delivered'=>'Partial Delivered',
		'cancelled'=>'Cancelled',
	];
	return $array;
}


function getMethods(){

	return [
		'card'=>'Card',
		'bank'=>'Bank',
		'cash'=>'Cash',
		'mobile_banking'=>'Mobile Banking',
	];
}



function orderStatus($order){

	if ($order) {
		$due=$order->final_amount - $order->payments()->sum('amount');
		if ($due==0) {
			$status='paid';
		}else if($due ==$order->final_amount){
			$status='due';
		}else{
			$status='partial';
		}

		$order->payment_status=$status;
		$order->save();
	}
	return true;

}


function transactionStatus($order){

	if ($order) {
		$due=$order->final_amount - $order->payments()->sum('amount');
		if ($due==0) {
			$status='paid';
		}else if($due ==$order->final_amount){
			$status='due';
		}else{
			$status='partial';
		}

		$order->payment_status=$status;
		$order->save();
	}
	return true;

}


function segment1($url){

	$res=request()->segment(2)==$url?true:false;
	return $res;
}

function segment2($url=null){

	$res=request()->segment(3)==$url?true:false;
	return $res;
}

function getIndDate($num){
	$date=date('d-m-Y');

	if($num !=0){
		$date=date('d-m-Y', strtotime($date . ' +'.$num.' day'));
	}
	return $date;
}

function getBusinessId(){

	return auth()->user()->business_id;
}

function isSuperAdmin(){

	return auth()->user()->id==1 ?true:false;
}

function isActive($routes){
    foreach ((array) $routes as $route) {
        if (request()->routeIs($route)) {
            return 'active';
        }
    }
    return '';
}


function getProductDiscount($product){
    $now = date('Y-m-d');

    $discount = Discount::where('status', 1)
        ->whereDate('start', '<=', $now)
        ->whereDate('end', '>=', $now)
        ->where(function ($q) use ($product) {

            // Brand OR Category discount
            $q->where('brand_id', $product->brand_id)
              ->orWhere('category_id', $product->category_id);

            // Specific product discount
            $q->orWhereHas('discount_prodcuts', function ($sub_q) use ($product) {
                $sub_q->where('product_id', $product->id);
            });

        })
        ->orderBy('priority')
        ->first();

    $discount_price = 0;
    
    if ($discount) {
        if ($discount->discount_type == 'percentage') {
            $discount_price = (($product->sell_price??$product->price) * $discount->amount) / 100;
        } else { // fixed
            $discount_price = $discount->amount;
        }
    }
    

    return [
        'discount_price' => $discount_price,
        'discount'       => $discount
    ];
}



// discount priority list
function discountPriorityList(){
	return [
		["id" => 3, "label" => "Low"],
		["id" => 2, "label" => "Medium"],
		["id" => 1, "label" => "High"],
	];
}
function getDiscountPriorityNameById(int $id)
{
	$list = discountPriorityList();
	$ids = array_column($list, 'id');
	$index = array_search($id, $ids, true);

	return ($index !== false) ? $list[$index]['label'] : "Unknown";
}



/**
 * গ্লোবাল ফাংশন: ইউজার টাইপ আইডি দিলে নাম রিটার্ন করবে
 * ব্যবহার: user_type_label(6) -> 'Ecommerce Customer'
 */
if (!function_exists('user_type_label')) {
	function user_type_label(?int $userTypeId): string
	{
		return UserType::getLabel($userTypeId);
	}
}

/**
 * গ্লোবাল ফাংশন: ইউজারটি কাস্টমার টাইপ কিনা চেক করবে
 * ব্যবহার: is_customer(auth()->user()->user_type) -> true/false
 */
if (!function_exists('is_customer')) {
	function is_customer(?int $userTypeId): bool
	{
		return UserType::isCustomer($userTypeId);
	}
}

/**
 * গ্লোবাল ফাংশন: সম্পূর্ণ ইউজার টাইপের লিস্ট অ্যারে দিবে (ড্রপডাউনের জন্য)
 */
if (!function_exists('user_type_list')) {
	function user_type_list(): array
	{
		return UserType::list();
	}
}



// ক্লিয়ার এবং রিডেবল কোড
/*
use App\Utils\UserType;

if ($user->user_type === UserType::ADMIN) {
    // শুধুমাত্র অ্যাডমিন এক্সেস পাবে
}

<!-- ইউজার টাইপের নাম সরাসরি প্রিন্ট করা -->
<p>User Type: {{ user_type_label($user->user_type) }}</p>

<!-- ড্রপডাউন জেনারেট করা -->
<select name="user_type">
    @foreach(user_type_list() as $id => $name)
        <option value="{{ $id }}">{{ $name }}</option>
    @endforeach
</select>
*/