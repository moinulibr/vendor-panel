<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class VendorFollowController extends Controller
{
    public function toggleFollow($vendorId)
    {
        $customerId = Auth::id();

        if ($customerId == $vendorId) {
            return response()->json(['error' => 'You cannot follow yourself']);
        }

        $exists = DB::table('vendor_followers')
            ->where('vendor_id', $vendorId)
            ->where('customer_id', $customerId)
            ->exists();

        if ($exists) {
            DB::table('vendor_followers')
                ->where('vendor_id', $vendorId)
                ->where('customer_id', $customerId)
                ->delete();

            return response()->json([
                'status' => 'unfollowed'
            ]);
        } else {
            DB::table('vendor_followers')->insert([
                'vendor_id' => $vendorId,
                'customer_id' => $customerId,
                'created_at' => now(),
                'updated_at' => now(),
            ]);

            return response()->json([
                'status' => 'followed'
            ]);
        }
    }
}
