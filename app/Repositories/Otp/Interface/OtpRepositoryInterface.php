<?php

namespace App\Repositories\Otp\Interface;

use App\Models\Otp;

interface OtpRepositoryInterface
{
    public function invalidatePreviousOtps(string $mobile, string $purpose): void;
    public function createOtp(array $data): Otp;
    public function findValidOtp(string $mobile, string $purpose): ?Otp;
    public function markAsUsed(Otp $otp): void;
}