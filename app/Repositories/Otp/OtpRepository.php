<?php

namespace App\Repositories\Otp;

use App\Models\Otp;
use App\Repositories\Otp\Interface\OtpRepositoryInterface;

class OtpRepository implements OtpRepositoryInterface
{
    public function invalidatePreviousOtps(string $mobile, string $purpose): void
    {
        Otp::where('mobile', $mobile)
            ->where('purpose', $purpose)
            ->where('is_used', false)
            ->update(['is_used' => true]);
    }

    public function createOtp(array $data): Otp
    {
        return Otp::create($data);
    }

    public function findValidOtp(string $mobile, string $purpose): ?Otp
    {
        return Otp::where('mobile', $mobile)
            ->where('purpose', $purpose)
            ->where('is_used', false)
            ->where('expires_at', '>', now())
            ->latest()
            ->first();
    }

    public function markAsUsed(Otp $otp): void
    {
        $otp->update(['is_used' => true]);
    }
}