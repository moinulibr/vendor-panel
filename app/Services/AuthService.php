<?php

namespace App\Services;

use App\Models\RetailerShippingAddress;
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

        if ($user->deleted_at != null || $user->status == 0 || $user->status == 4) {
            throw new Exception("User not found!.", 403);
        }
        if ($user->status == 2) {
            throw new Exception("User is Inactive!.", 403);
        }
        if ($user->status == 3) {
            throw new Exception("User is Suspended!.", 403);
        }
        if ($user->status == 5) {
            throw new Exception("User is Blocked!.", 403);
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
                throw new Exception("User not found।", 404);
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
                'user_type'   => $data['user_type'] ?? UserType::GENERAL_APP_CUSTOMER,
                'access_type' => (int) $data['access_type'] ?? UserType::EXTERNAL_ACCESS_TYPE,
            ]);

            if ((int)$data['access_type'] === UserType::EXTERNAL_ACCESS_TYPE && $data['user_type'] == UserType::DEALER || $data['user_type'] == UserType::GENERAL_APP_CUSTOMER) {
                $this->userRepo->createRetailer([
                    'user_id'   => $user->id,
                    'shop_name' => $data['shop_name'] ?? null,
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
            throw new Exception("User not found।", 404);
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
            throw new Exception("User not found।", 404);
        }

        if (!$user->password || !Hash::check($data['current_password'], $user->password)) {
            throw new Exception("Your current password does not match।", 422);
        }

        $this->userRepo->updatePassword($user, Hash::make($data['password']));
    }


    public function getProfile(User $user): array
    {
        return [
            'user' => $user->load('retailer'),
        ];
    }

    public function updateProfile(User $user, array $data): User
    {
        // Handle license image upload if present
        if (isset($data['license_image']) && $data['license_image'] instanceof \Illuminate\Http\UploadedFile) {
            // Delete old image if exists
            if ($user->retailer && $user->retailer->license_image) {
                \Illuminate\Support\Facades\Storage::disk('public')->delete($user->retailer->license_image);
            }

            // Store new image
            $data['license_image'] = $data['license_image']->store('trade_licenses', 'public');
        }

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
            throw new Exception("Your OTP does not match", 422);
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
        $data['created_by'] = auth()->user()->id;
        return $this->userRepo->createRetailerShippingAddress($data);
    }

    public function getRetailerShippingAddress(int $retailerId)
    {
        if(!$this->userRepo->findRetailerById($retailerId)) {
            throw new Exception("Retailer not found", 404);
        }
        return $this->userRepo->getRetailerShippingAddresses($retailerId);
    }

    public function updateRetailerShippingAddress(int $retailerId, int $shippingAddressId, array $data): RetailerShippingAddress
    {
        $retailerShippingAddress = $this->userRepo->getRetailerSingleShippingAddress($shippingAddressId);
        if (!$retailerShippingAddress) {
            throw new Exception("Retailer Shipping Address not found।", 404);
        }
        $data['retailer_id'] = $retailerId;
        return $this->userRepo->updateRetailerShippingAddress($retailerShippingAddress, $data);
    }

    public function deleteRetailerShippingAddress(int $shippingAddressId, int $retailerId)
    {
        return $this->userRepo->deleteRetailerShippingAddress($shippingAddressId, $retailerId);
    }

    public function getVendorList(array $filters)
    {
        $perPage = $filters['per_page'] ?? 20;
        return $this->userRepo->getVendors($filters, $perPage);
    }

    public function getRetailerList(array $filters)
    {
        $perPage = $filters['per_page'] ?? 20;
        return $this->userRepo->getRetailers($filters, $perPage);
    }

    /**
     * switch User tye function
     *
     * @param array $data
     * @return array
     */
    public function switchUserType(array $data): array
    {
        DB::beginTransaction();
        try {
            // Handle Image Upload if exists
            if (isset($data['license_image']) && $data['license_image'] instanceof \Illuminate\Http\UploadedFile) {
                $path = $data['license_image']->store('trade_licenses', 'public');
                $data['license_image'] = $path;
            }

            // 1. Create or Update Retailer Table
            $retailer = $this->userRepo->createOrUpdateRetailer([
                'user_id'       => $data['user_id'],
                'shop_name'     => $data['shop_name'],
                'trade_license' => $data['trade_license'] ?? null,
                'license_image' => $data['license_image'] ?? null,
            ]);

            // 2. Update User Table user_type
           $user = $this->userRepo->updateUserType($data['user_id'], $data['to_user_type_id']);

            DB::commit();

            return [
                'user' => $this->userRepo->findById($data['user_id'])
            ];
        } catch (Exception $e) {
            DB::rollBack();

            // Delete uploaded file if transaction fails
            if (isset($path) && Storage::disk('public')->exists($path)) {
                Storage::disk('public')->delete($path);
            }

            throw new Exception($e->getMessage(), 500);
        }
    }
}