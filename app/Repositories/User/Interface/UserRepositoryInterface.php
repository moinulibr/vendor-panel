<?php

namespace App\Repositories\User\Interface;

use App\Models\User;

interface UserRepositoryInterface
{
    public function findByCredentials(string $loginCredential);
    public function findById(int $id);
    public function createUser(array $data): User;
    public function createRetailer(array $data);
    public function updatePassword(User $user, string $newPassword): bool;
    public function deleteAccount(User $user): bool;
}