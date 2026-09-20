<?php

namespace App\Repositories\User;

use App\Models\Retailer;
use App\Models\RetailerShippingAddress;
use App\Models\ShippingAddress;
use App\Models\User;
use App\Models\UserDetail;
use App\Repositories\User\Interface\UserRepositoryInterface;
use App\Utils\UserType;
use Illuminate\Support\Facades\DB;
use Illuminate\Contracts\Pagination\Paginator;

class UserRepository implements UserRepositoryInterface
{
    public function findByCredentials(string $loginCredential)
    {
        return User::where('mobile', $loginCredential)->with('retailer')->first();
    }
    
    public function findByMobileNumber(string $mobile)
    {
        return User::where('mobile', $mobile)->first();
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
            'access_type' => $data['access_type'] ?? UserType::EXTERNAL_ACCESS_TYPE, ///UserType::DEALER  UserType::EXTERNAL_ACCESS_TYPE,
            'user_type' => $data['user_type'] ?? UserType::DEALER
        ]);
    }

    public function updateProfile(User $user, array $data): User
    {
        return DB::transaction(function () use ($user, $data) {
            // Update User Base Info
            $userData = [
                'name' => $data['name'],
            ];

            if (isset($data['email'])) {
                $userData['email'] = $data['email'] ?? $user->email;
            }

            if (isset($data['mobile'])) {
                $userData['mobile'] = $data['mobile'] ?? $user->mobile;
            }

            $user->update($userData);

            // Update or Create Retailer Info if relevant fields are passed
            if (
                array_key_exists('shop_name', $data) ||
                array_key_exists('address', $data) ||
                array_key_exists('trade_license', $data) ||
                array_key_exists('license_image', $data)
            ) {
                $userDetailData = [];

                if (array_key_exists('shop_name', $data)) {
                    $userDetailData['shop_name'] = $data['shop_name'];
                }
                if (array_key_exists('address', $data)) {
                    $userDetailData['address'] = $data['address'];
                }
                if (array_key_exists('trade_license', $data)) {
                    $userDetailData['trade_license'] = $data['trade_license'];
                }
                if (array_key_exists('license_image', $data)) {
                    $userDetailData['license_image'] = $data['license_image'];
                }

                $user->userDetail()->updateOrCreate(
                    ['user_id' => $user->id],
                    $userDetailData
                );
            }

            return $user->load('userDetail');
        });

        /*
            return DB::transaction(function () use ($user, $data) {
                $user->update([
                    'name'   => $data['name'],
                    'email'  => $data['email'] ?? $user->email,
                    'mobile' => $data['mobile'] ?? $user->mobile,
                ]);

                if (isset($data['shop_name']) || isset($data['address']) || isset($data['trade_license'])) {
                    $user->retailer()->updateOrCreate(
                        ['user_id' => $user->id],
                        [
                            'shop_name'     => $data['shop_name'] ?? optional($user->retailer)->shop_name,
                            'address'       => $data['address'] ?? optional($user->retailer)->address,
                            'trade_license' => $data['trade_license'] ?? optional($user->retailer)->trade_license,
                        ]
                    );
                }

                return $user->load('retailer');
            });
        */
    }

    public function createUserDetail(array $data)
    {
        return UserDetail::create([
            'user_id'   => $data['user_id'],
            'type' => $data['type'] ?? UserType::MOBILE_APP_TYPE_FOR_USER_DETAIL,
            'shop_name' => $data['shop_name'] ?? null,
            'address'   => $data['address'] ?? null,
            'trade_license' => $data['trade_license'] ?? null,
            //and others fields will be added here
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
        // Delete related user detail data if exists
        if ($user->userDetail()->exists()) {
            $user->userDetail()->update(['status' => 'deleted']);
        }

        $mobile = "d_" . $user->mobile;
        $email = "d_" . $user->email;
        $user->update(['mobile' => $mobile, 'email' => $email, 'status' => 0, 'deleted_at' => now()]);

        // Revoke all tokens and delete user
        $user->tokens()->delete();
        return true;
    }


    public function updateProfilePicture(User $user, string $avatarPath): bool
    {
        return $user->update(['image' => $avatarPath]);
    }

    public function findUserDetailById(int $userDetailId)
    {
        return UserDetail::where('id', $userDetailId)->where('status', '!=', 'deleted')->first();
    }


    public function createShippingAddress(array $data): ShippingAddress
    {
        if (!empty($data['is_default']) && $data['is_default']) {
            ShippingAddress::where('user_detail_id', $data['user_detail_id'])->where('user_id', $data['user_id'])->where('type',UserType::MOBILE_APP_TYPE_FOR_USER_DETAIL)->update(['is_default' => false]);
        }

        return ShippingAddress::create($data);
    }

    public function findRetailerById(int $retailerId)
    {
        return Retailer::where('id', $retailerId)->where('status','!=','deleted')->first();
    }
    //have to modify
    public function getRetailerSingleShippingAddress(int $shippingAddressId)
    {
        return RetailerShippingAddress::where('id', $shippingAddressId)->whereNull('deleted_at')->first();
    }
    //have to modify
    public function getShippingAddresses(int $userId, int $userDetailId)
    {
        return ShippingAddress::where('user_id', $userId)
            ->where('user_detail_id', $userDetailId)
            ->where('type', UserType::MOBILE_APP_TYPE_FOR_USER_DETAIL)
            ->where('status', 'active')
            ->whereNull('deleted_at')
            ->get();
    }

    //have to modify
    public function updateRetailerShippingAddress(RetailerShippingAddress $retailerShippingAddress, array $data): RetailerShippingAddress
    {
        $isDefault = $retailerShippingAddress->is_default;
        if (!empty($data['is_default']) && $data['is_default'] && $isDefault == false) {
            RetailerShippingAddress::where('retailer_id', $data['retailer_id'])->update(['is_default' => false]);
        }
        $data['is_default'] = empty($data['is_default']) ? false : true;

        $retailerShippingAddress->update($data);
        return $retailerShippingAddress;
    }
    
    public function deleteRetailerShippingAddress(int $addressId, int $retailerId): bool
    {
        return RetailerShippingAddress::where('id', $addressId)->where('retailer_id', $retailerId)->update(['deleted_at' => now()]);
    }


    public function getVendors(array $filters, int $perPage = 20): Paginator
    {
        $query = User::with('userDetail')->where('user_type', UserType::VENDOR)
            ->where('access_type', UserType::EXTERNAL_ACCESS_TYPE)
            ->whereNull('deleted_at');

        return $this->applyUserFiltersAndPaginate($query, $filters, $perPage);
    }

    public function getUsers(array $filters, array $userTypes = [UserType::DEALER, UserType::GENERAL_APP_CUSTOMER], int $perPage = 20): Paginator
    {
        $query = User::whereIn('user_type', $userTypes)
            ->where('access_type', UserType::EXTERNAL_ACCESS_TYPE)
            ->where('status', 1)
            ->whereNull('deleted_at')
            ->with('userDetail');

        return $this->applyUserFiltersAndPaginate($query, $filters, $perPage);
    }

    private function applyUserFiltersAndPaginate($query, array $filters, int $perPage): Paginator
    {
        if (!empty($filters['q'])) {
            $search = $filters['q'];
            $query->where(function ($row) use ($search) {
                $row->where('name', 'LIKE', '%' . $search . '%')
                    ->orWhere('email', 'LIKE', '%' . $search . '%')
                    ->orWhere('mobile', 'LIKE', '%' . $search . '%');
            });
        }

        if (isset($filters['status']) && $filters['status'] !== '') {
            $status = ($filters['status'] === 'active' || $filters['status'] == 1) ? 1 : 0;
            $query->where('status', $status);
        }

        $sort = $filters['sort'] ?? 'desc';
        if ($sort === 'asc') {
            $query->orderBy('id', 'asc');
        } else {
            $query->orderBy('id', 'desc');
        }

        return $query->simplePaginate($perPage);
    }

    //Create or Update User Detail
    public function createOrUpdateUserDetail(array $data): UserDetail
    {
        return UserDetail::updateOrCreate(
            ['id' => $data['user_detail_id'],'user_id' => $data['user_id']],
            [
                'shop_name'     => $data['shop_name'] ?? null,
                'type'          => $data['type'] ?? UserType::MOBILE_APP_TYPE_FOR_USER_DETAIL,
                'trade_license' => $data['trade_license'] ?? null,
                'license_image' => $data['license_image'] ?? null,
                'address'       => $data['address'],
                'status'        => $data['status'] ?? 'active',
            ]
        );
    }

    public function createOrUpdateRetailer(array $data): Retailer
    {
        return Retailer::updateOrCreate(
            ['id' => $data['retailer_id'],'user_id' => $data['user_id']],
            [
                'shop_name'     => $data['shop_name'] ?? null,
                'trade_license' => $data['trade_license'] ?? null,
                'license_image' => $data['license_image'] ?? null,
                'status'        => $data['status'] ?? 'active',
            ]
        );
    }

    //update user type
    public function updateUserType(int $userId, int $toUserTypeId): bool
    {
        return User::where('id', $userId)->update([
            'user_type' => $toUserTypeId
        ]);
    }
}