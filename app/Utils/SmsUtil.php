<?php

namespace App\Utils;

use App\Models\SmsSetting;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SmsUtil
{
    /**
     * Send SMS using Dynamic Gateway Config stored in Database (with Caching)
     */
    public static function sendSms(string $number, string $message): bool
    {
        // DB Query কমানোর জন্য ২৪ ঘণ্টার জন্য ক্যাশ করা
        $setting = Cache::remember('sms_setting', 86400, function () {
            return SmsSetting::first();
        });

        if (!$setting) {
            Log::error('SMS Gateway settings missing in database.');
            return false;
        }

        // Dynamic Payload Build করা (Null/Empty key বাতিল করবে)
        $payload = array_filter([
            $setting->send_to => $number,
            $setting->message => $message,
            $setting->key_1   => $setting->key_value_1,
            $setting->key_2   => $setting->key_value_2,
            $setting->key_3   => $setting->key_value_3,
            $setting->key_4   => $setting->key_value_4,
        ]);

        try {
            if (strtolower($setting->method) === 'post') {
                $response = Http::asForm()->post($setting->url, $payload);
            } else {
                $response = Http::get($setting->url, $payload);
            }

            return $response->successful();
        } catch (\Exception $e) {
            Log::error('SMS Gateway Error: ' . $e->getMessage());
            return false;
        }
    }
}
