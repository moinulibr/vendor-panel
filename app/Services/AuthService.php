<?php

namespace App\Services;

use App\Repositories\User\Interface\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use Exception;

class AuthService
{
    protected $userRepo;

    public function __construct(UserRepositoryInterface $userRepo)
    {
        $this->userRepo = $userRepo;
    }

    public function authenticate(array $credentials)
    {
        $user = $this->userRepo->findByCredentials($credentials['mobile']);

        if (!$user || !Hash::check($credentials['password'], $user->password)) {
            throw new Exception("Invalid login credentials provided.", 401);
        }

        //if (!in_array($user->user_type, [2, 2])) {
          //  throw new Exception("Unauthorized access. Only SR and Merchants can access this app.", 403);
        //}

        //if (!$user->is_active) {
          //  throw new Exception("Your account status is inactive. Contact Admin.", 403);
        //}

        // Generate Token
        $token = $user->createToken('app-mobile-access-token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token
        ];
    }
}