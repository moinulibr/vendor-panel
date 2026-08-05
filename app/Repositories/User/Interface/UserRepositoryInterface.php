<?php

namespace App\Repositories\User\Interface;

use App\Models\RetailerShippingAddress;
use App\Models\User;

interface UserRepositoryInterface
{
    public function findByCredentials(string $loginCredential);
    public function findByMobileNumber(string $loginCredential);
    public function findById(int $id);
    public function createUser(array $data): User;
    public function createRetailer(array $data);
    public function updatePassword(User $user, string $newPassword): bool;
    public function deleteAccount(User $user): bool;
    public function updateProfilePicture(User $user, string $profilePicturePath): bool;
    public function updateProfile(User $user, array $data): User;

    public function findRetailerById(int $retailerId);
    // Address management
    public function createRetailerShippingAddress(array $data): RetailerShippingAddress;
    public function getRetailerShippingAddresses(int $retailerId);
    public function deleteRetailerShippingAddress(int $addressId, int $retailerId): bool;
}