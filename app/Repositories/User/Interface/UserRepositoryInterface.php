<?php

namespace App\Repositories\User\Interface;

interface UserRepositoryInterface
{
    public function findByCredentials(string $loginCredential);
    public function findById(int $id);
}