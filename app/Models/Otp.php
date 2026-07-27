<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use OpenApi\Attributes as OA;

#[OA\Schema(
    schema: "Otp",
    title: "Otp Model",
    description: "OTP Model"
)]
class Otp extends Model
{
    use HasFactory;

    protected $fillable = [
        'mobile',
        'code',
        'purpose',
        'expires_at',
        'is_used',
        'attempts',
    ];

    protected $casts = [
        'expires_at' => 'datetime',
        'is_used'    => 'boolean',
    ];

    public function scopeValid($query, $mobile, $purpose)
    {
        return $query->where('mobile', $mobile)
            ->where('purpose', $purpose)
            ->where('is_used', false)
            ->where('expires_at', '>', now());
    }
}