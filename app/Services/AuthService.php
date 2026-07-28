<?php

namespace App\Services;

use App\Repositories\User\Interface\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use App\Models\Retailer;
use App\Repositories\Otp\Interface\OtpRepositoryInterface;
use App\Utils\SmsUtil;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;

class AuthService
{

    protected UserRepositoryInterface $userRepo;
    protected OtpRepositoryInterface $otpRepo;

    public function __construct(UserRepositoryInterface $userRepo, OtpRepositoryInterface $otpRepo)
    {
        $this->userRepo = $userRepo;
        $this->otpRepo  = $otpRepo;
    }


    public function authenticate(array $credentials)
    {
        $user = $this->userRepo->findByCredentials($credentials['mobile']);

        if (($credentials['login_type'] ?? 'password') === 'password') {
            $userPassword = $user ? $user->password : null;
            if (!Hash::check($credentials['password'], $userPassword)) {
                throw new Exception("Invalid login credentials provided.", 401);
            }
        } else {
            $this->verifyOtpCode($credentials['mobile'], $credentials['otp'], 'login');
        }
        // SR and Retailer Access Check (access_type == 2)
        if ((int)$user->access_type !== 2) {
            throw new Exception("Unauthorized access. Only SR and Retailer can access this app.", 403);
        }

        // Generate Token
        $token = $user->createToken('app-mobile-access-token')->plainTextToken;

        return [
            'user'  => $user,
            'token' => $token
        ];
    }
    
    public function sendOtp(array $data): string
    {
        $code = config('app.env') === 'local' ? '1234' : (string) rand(1000, 9999);

        $this->otpRepo->invalidatePreviousOtps($data['mobile'], $data['purpose']);

        $this->otpRepo->createOtp([
            'mobile'     => $data['mobile'],
            'code'       => $code,
            'purpose'    => $data['purpose'],
            'expires_at' => now()->addMinutes(5),
        ]);

        $msg = "Your OTP for verification is: {$code}. Valid for 5 minutes.";
        //Sending sms to mobile is temporarily disabled for randomly testing 
        if(sendingOptToMobile()){
            SmsUtil::sendSms($data['mobile'], $msg);
        }

        return $code;
    }

    public function registerUser(array $data): array
    {
        //$this->verifyOtpCode($data['mobile'], $data['otp'], 'register');

        return DB::transaction(function () use ($data) {
            $user = $this->userRepo->createUser([
                'name'        => $data['name'] ?? null,
                'email'        => $data['email'] ?? null,
                'mobile'      => $data['mobile'],
                'password'    => isset($data['password']) ? Hash::make($data['password']) : null,
                'access_type' => (int) $data['access_type'] ?? 2,
            ]);

            if ((int)$data['access_type'] === 2 && !empty($data['shop_name'])) {
                $this->userRepo->createRetailer([
                    'user_id'   => $user->id,
                    'shop_name' => $data['shop_name'],
                    'address'   => $data['address'] ?? null,
                    'trade_license'   => $data['trade_license'] ?? null,
                ]);
            }

            $token = $user->createToken('app-mobile-access-token')->plainTextToken;

            return [
                'user'  => $user->load('retailer'),
                'token' => $token,
            ];
        });
    }

    public function resetPassword(array $data): void
    {
        $this->verifyOtpCode($data['mobile'], $data['otp'], 'reset_password');

        $user = $this->userRepo->findByCredentials($data['mobile']);
        if (!$user) {
            throw new Exception("ইউজার পাওয়া যায়নি।", 404);
        }

        $this->userRepo->updatePassword($user, Hash::make($data['password']));
    }

    public function getProfile($user): array
    {
        return [
            'user' => $user->load('retailer'),
        ];
    }

    public function deleteAccount($user): void
    {
        $this->userRepo->deleteAccount($user);
    }
    /*
    public function registerUser(array $data): array
    {
        $this->verifyOtpCode($data['mobile'], $data['otp'], 'register');

        return DB::transaction(function () use ($data) {
            $user = $this->userRepo->create([
                'name'        => $data['name'],
                'mobile'      => $data['mobile'],
                'password'    => isset($data['password']) ? Hash::make($data['password']) : null,
                'access_type' => 2, // Default 2 for Mobile App SR/Retailer
            ]);

            if ($data['user_type'] === 'retailer') {
                Retailer::create([
                    'user_id'   => $user->id,
                    'shop_name' => $data['shop_name'] ?? null,
                    'address'   => $data['address'] ?? null,
                ]);
            }

            $token = $user->createToken('app-mobile-access-token')->plainTextToken;

            return [
                'user'  => $user->load('retailer'),
                'token' => $token,
            ];
        });
    }

    public function resetPassword(array $data): void
    {
        $this->verifyOtpCode($data['mobile'], $data['otp'], 'reset_password');

        $user = $this->userRepo->findByCredentials($data['mobile']);
        if (!$user) {
            throw new Exception("User not found.", 404);
        }

        $user->update(['password' => Hash::make($data['password'])]);
    }
    */

    private function verifyOtpCode(string $mobile, string $code, string $purpose): void
    {
        $otp = $this->otpRepo->findValidOtp($mobile, $purpose);

        if (!$otp || $otp->code !== $code) {
            throw new Exception("Invalid or expired OTP provided.", 422);
        }

        $this->otpRepo->markAsUsed($otp);
    }

    /*
    public function sendOtp(string $mobile, string $purpose): array
    {
        $code = config('app.env') === 'local' ? '1234' : (string) rand(1000, 9999);

        // আগের অব্যবহৃত OTP নিষ্ক্রিয় করা
        Otp::where('mobile', $mobile)
            ->where('purpose', $purpose)
            ->where('is_used', false)
            ->update(['is_used' => true]);

        Otp::create([
            'mobile'     => $mobile,
            'code'       => $code,
            'purpose'    => $purpose,
            'expires_at' => now()->addMinutes(5),
        ]);

        // Send SMS via Utility
        $msg = "Your OTP for verification is: {$code}. Valid for 5 minutes.";
        SmsUtil::sendSms($mobile, $msg);

        return [
            'code' => $code,
        ];
    }

    public function verifyOtp(string $mobile, string $code, string $purpose): bool
    {
        $otp = Otp::valid($mobile, $purpose)->latest()->first();

        if (!$otp || $otp->code !== $code) {
            return false;
        }

        $otp->update(['is_used' => true]);
        return true;
    }

    public function registerUser(array $data): User
    {
        return DB::transaction(function () use ($data) {
            $user = User::create([
                'name'     => $data['name'],
                'mobile'   => $data['mobile'],
                'password' => isset($data['password']) ? Hash::make($data['password']) : null,
                'role'     => $data['user_type'],
            ]);

            if ($data['user_type'] === 'retailer') {
                Retailer::create([
                    'user_id'   => $user->id,
                    'shop_name' => $data['shop_name'] ?? null,
                    'address'   => $data['address'] ?? null,
                ]);
            }

            return $user;
        });
    }

    public function resetPassword(string $mobile, string $newPassword): void
    {
        User::where('mobile', $mobile)->update([
            'password' => Hash::make($newPassword)
        ]);
    }
        */
}