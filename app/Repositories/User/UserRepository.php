<?php

namespace App\Repositories\User;

use App\Models\Retailer;
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
    
}