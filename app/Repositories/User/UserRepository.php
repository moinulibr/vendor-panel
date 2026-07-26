<?php

namespace App\Repositories\User;
use App\Models\User;
use App\Repositories\User\Interface\UserRepositoryInterface;

class UserRepository implements UserRepositoryInterface
{
    public function findByCredentials(string $loginCredential)
    {
        return User::where('email', $loginCredential)
            ->orWhere('phone', $loginCredential)
            //->orWhere('employee_id', $loginCredential)
            ->first();
    }

    public function findById(int $id)
    {
        return User::findOrFail($id);
    }
}