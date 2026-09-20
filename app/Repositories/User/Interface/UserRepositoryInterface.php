<?php

namespace App\Repositories\User\Interface;

use App\Models\Retailer;
use Illuminate\Contracts\Pagination\Paginator;
use App\Models\RetailerShippingAddress;
use App\Models\ShippingAddress;
use App\Models\User;
use App\Models\UserDetail;
use App\Utils\UserType;

interface UserRepositoryInterface
{
    public function findByCredentials(string $loginCredential);
    public function findByMobileNumber(string $loginCredential);
    public function findById(int $id);
    public function createUser(array $data): User;
    public function createUserDetail(array $data);
    public function createRetailer(array $data);
    public function updatePassword(User $user, string $newPassword): bool;
    public function deleteAccount(User $user): bool;
    public function updateProfilePicture(User $user, string $profilePicturePath): bool;
    public function updateProfile(User $user, array $data): User;

    public function findUserDetailById(int $userDetailId);
    public function findRetailerById(int $retailerId);
    // Address management
    public function createShippingAddress(array $data): ShippingAddress;
    public function updateRetailerShippingAddress(RetailerShippingAddress $shippingAddressId, array $data): RetailerShippingAddress;
    public function getShippingAddresses(int $retailerId);
    public function getRetailerSingleShippingAddress(int $shippingAddressId);
    public function deleteRetailerShippingAddress(int $shippingAddressId, int $retailerId): bool;

    public function getVendors(array $filters, int $perPage = 20): Paginator;
    public function getUsers(array $filters, array $userTypes = [UserType::DEALER, UserType::GENERAL_APP_CUSTOMER], int $perPage = 20): Paginator;

    public function createOrUpdateUserDetail(array $data): UserDetail;
    public function createOrUpdateRetailer(array $data): Retailer;
    public function updateUserType(int $userId, int $toUserTypeId): bool;
}