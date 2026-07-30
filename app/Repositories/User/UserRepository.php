<?php

namespace App\Repositories\User;

use App\Models\Retailer;
use App\Models\RetailerShippingAddress;
use App\Models\User;
use App\Repositories\User\Interface\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function findByCredentials(string $loginCredential)
    {
        return User::where('mobile', $loginCredential)
            //->orWhere('phone', $loginCredential)
            //->orWhere('employee_id', $loginCredential)
            ->first();
        return User::where('email', $loginCredential)
            ->orWhere('phone', $loginCredential)
            //->orWhere('employee_id', $loginCredential)
            ->first();
    }
    
    public function findByMobileNumber(string $mobile)
    {
        return User::where('mobile', $mobile)
            ->first();
    }

    public function findById(int $id)
    {
        return User::findOrFail($id);
    }

    public function createUser(array $data): User
    {
        return User::create([
            'name'        => $data['name'],
            'mobile'      => $data['mobile'],
            'email'       => $data['email'] ?? null,
            'password'    => $data['password'] ?? null,
            'status'      => 1,
            'access_type' => $data['access_type'] ?? 2,
        ]);
    }

    public function createRetailer(array $data)
    {
        return Retailer::create([
            'user_id'   => $data['user_id'],
            'shop_name' => $data['shop_name'] ?? null,
            'address'   => $data['address'] ?? null,
            'trade_license' => $data['trade_license'] ?? null,
            //and others fields will be added here
        ]);
    }

    public function updatePassword(User $user, string $newPassword): bool
    {
        return $user->update(['password' => $newPassword]);
    }

    public function deleteAccount(User $user): bool
    {
        // Delete related retailer data if exists
        if ($user->retailer) {
            $user->retailer()->update(['status' => 'deleted']);
        }

        $mobile = "d_" . $user->mobile;
        $email = "d_" . $user->email;
        $user->update(['mobile' => $mobile, 'email' => $email, 'status' => 0]);

        // Revoke all tokens and delete user
        $user->tokens()->delete();
        return true;
    }


    public function updateProfilePicture(User $user, string $avatarPath): bool
    {
        return $user->update(['image' => $avatarPath]);
    }

    public function createRetailerShippingAddress(array $data): RetailerShippingAddress
    {
        if (!empty($data['is_default']) && $data['is_default']) {
            RetailerShippingAddress::where('retailer_id', $data['retailer_id'])->update(['is_default' => false]);
        }

        return RetailerShippingAddress::create($data);
    }

    public function findRetailerById(int $retailerId)
    {
        return Retailer::where('id', $retailerId)->where('status','!=','deleted')->first();
    }

    public function getRetailerShippingAddresses(int $retailerId)
    {
        return RetailerShippingAddress::where('retailer_id', $retailerId)->whereNull('deleted_at')->get();
    }

    public function deleteRetailerShippingAddress(int $addressId, int $retailerId): bool
    {
        return RetailerShippingAddress::where('id', $addressId)->where('retailer_id', $retailerId)->delete();
    }
}