<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;

class SettingController extends Controller
{
    /**
     * Get public store settings for customer storefront
     */
    public function index(): JsonResponse
    {
        try {
            $settings = Setting::all();

            $publicSettings = [];
            $grouped = [];

            foreach ($settings as $setting) {
                // Do not expose sensitive secrets to customer API
                if (in_array($setting->key, ['razorpay_key_secret', 'mail_password', 'jwt_secret', 'api_secret'])) {
                    continue;
                }

                $val = $setting->value;
                if ($setting->type === 'checkbox' || $setting->type === 'boolean') {
                    $val = filter_var($val, FILTER_VALIDATE_BOOLEAN);
                } elseif (in_array($setting->type, ['number', 'integer'])) {
                    $val = is_numeric($val) ? (int)$val : 0;
                } elseif (in_array($setting->type, ['decimal', 'float'])) {
                    $val = is_numeric($val) ? (float)$val : 0.0;
                }

                $publicSettings[$setting->key] = $val;
                $grouped[$setting->group][$setting->key] = $val;
            }

            return response()->json([
                'success' => true,
                'data' => $publicSettings,
                'groups' => $grouped,
                'message' => 'Public store settings retrieved successfully'
            ]);
        } catch (\Throwable $e) {
            \Log::error('Customer SettingController index error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to load store settings'
            ], 500);
        }
    }
}
