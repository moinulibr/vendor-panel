<?php

namespace App\Services;

use App\Models\User;
use App\Repositories\User\Interface\UserRepositoryInterface;
use Illuminate\Support\Facades\Hash;
use App\Repositories\Otp\Interface\OtpRepositoryInterface;
use App\Utils\SmsUtil;
use App\Utils\UserType;
use Illuminate\Support\Facades\DB;
use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

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
        
        if($data['check_user'] === 'exist'){
            $user = $this->userRepo->findByCredentials($data['mobile']);
            if (!$user) {
                throw new Exception("ইউজার পাওয়া যায়নি।", 404);
            }
        }

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
                'user_type'   => $data['user_type'] ?? UserType::RETAILER,
                'access_type' => (int) $data['access_type'] ?? UserType::EXTERNAL_ACCESS_TYPE,
            ]);

            if ((int)$data['access_type'] === UserType::EXTERNAL_ACCESS_TYPE && !empty($data['shop_name'])) {
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
        $user = $this->userRepo->findByCredentials($data['mobile']);
        if (!$user) {
            throw new Exception("ইউজার পাওয়া যায়নি।", 404);
        }

        /*if ($data['reset_by'] === 'otp') {
            $this->verifyOtpCode($data['mobile'], $data['otp'], 'reset_password');
        } else {
            // Old Password ম্যাচিং চেক
            if (!$user->password || !Hash::check($data['old_password'], $user->password)) {
                throw new Exception("আপনার প্রদানকৃত বর্তমান পাসওয়ার্ডটি ভুল।", 422);
            }
        }*/

        $this->userRepo->updatePassword($user, Hash::make($data['password']));
    }

    public function changePassword(User $user,array $data): void
    {
        if (!$user) {
            throw new Exception("ইউজার পাওয়া যায়নি।", 404);
        }

        // Old Password ম্যাচিং চেক
        if (!$user->password || !Hash::check($data['current_password'], $user->password)) {
            throw new Exception("আপনার প্রদানকৃত বর্তমান পাসওয়ার্ডটি ভুল।", 422);
        }

        $this->userRepo->updatePassword($user, Hash::make($data['password']));
    }


    public function getProfile($user): array
    {
        return [
            'user' => $user->load('retailer'),
        ];
    }

    public function updateProfile(User $user, array $data): User
    {
        return $this->userRepo->updateProfile($user, $data);
    }
    
    public function deleteAccount($user): void
    {
        $this->userRepo->deleteAccount($user);
    }


    private function verifyOtpCode(string $mobile, string $code, string $purpose): void
    {
        $otp = $this->otpRepo->findValidOtp($mobile, $purpose);

        if (!$otp || $otp->code !== $code) {
            throw new Exception("Invalid or expired OTP provided.", 422);
        }

        $this->otpRepo->markAsUsed($otp);
    }

    public function verifyOtpOnly(array $data): void
    {
        $otp = $this->otpRepo->findValidOtp($data['mobile'], $data['purpose']);

        if (!$otp || $otp->code !== $data['otp']) {
            throw new Exception("অবৈধ বা মেয়াদোত্তীর্ণ ওটিপি প্রদান করা হয়েছে।", 422);
        }
        $this->otpRepo->markAsUsed($otp);
    }


    public function profilePictureUpdate($user, $imageFile): string
    {
        if ($user->image && Storage::disk('public')->exists($user->image)) {
            Storage::disk('public')->delete($user->image);
        }

        $path = $imageFile->store('avatars', 'public');
        $this->userRepo->updateProfilePicture($user, $path);

        return Storage::disk('public')->url($path);
    }

    public function addRetailerShippingAddress(int $retailerId, array $data)
    {
        $data['retailer_id'] = $retailerId;
        return $this->userRepo->createRetailerShippingAddress($data);
    }

    public function getRetailerShippingAddress(int $retailerId)
    {
        if(!$this->userRepo->findRetailerById($retailerId)) {
            throw new Exception("রিটেইলার পাওয়া যায়নি।", 404);
        }
        return $this->userRepo->getRetailerShippingAddresses($retailerId);
    }
}