<?php

namespace App\Http\Controllers\Api\Customer;

use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use App\Http\Controllers\Controller;

class CustomerApiAuthController extends Controller
{
    public function login(Request $request)
    {
        $request->validate([
            'email' => 'required|string',
            'password' => 'required|string'
        ]);

        $loginInput = trim($request->email);
        $cleanDigits = preg_replace('/\D/', '', $loginInput);

        $customer = Customer::where('email', strtolower($loginInput))
            ->orWhere('mobile', $loginInput)
            ->when(strlen($cleanDigits) >= 10, function ($query) use ($cleanDigits) {
                $last10 = substr($cleanDigits, -10);
                $query->orWhere('mobile', 'LIKE', "%{$last10}%");
            })
            ->first();

        if ($customer) {
            $info = password_get_info($customer->password);
            if ($info['algo'] === null || $info['algo'] === 0 || $info['algoName'] === 'unknown') {
                if ($customer->password === $request->password) {
                    $customer->password = Hash::make($request->password);
                    $customer->save();
                } else {
                    $customer->password = Hash::make($customer->password);
                    $customer->save();
                }
            }
        }

        if (!$customer || !Hash::check($request->password, $customer->password)) {
            return response()->json(['message' => 'Invalid email/phone or password.'], 401);
        }

        $token = $customer->createToken('customer_api')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'mobile' => $customer->mobile,
            ],
        ]);
    }

    public function register(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:customers',
            'mobile' => 'required|string|max:25',
            'password' => 'required|string|min:6',
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'email' => strtolower($request->email),
            'mobile' => $request->mobile,
            'password' => Hash::make($request->password),
            'status' => true,
        ]);

        $token = $customer->createToken('customer_api')->plainTextToken;

        return response()->json([
            'success' => true,
            'token' => $token,
            'user' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'mobile' => $customer->mobile,
            ],
        ], 201);
    }

    public function profile(Request $request)
    {
        $customer = $request->user();
        return response()->json([
            'success' => true,
            'user' => [
                'id' => $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'mobile' => $customer->mobile,
            ]
        ]);
    }

    public function logout(Request $request)
    {
        $request->user()->currentAccessToken()->delete();

        return response()->json(['success' => true, 'message' => 'Logged out']);
    }

    public function updateProfile(Request $request)
    {
        $customer = auth('customer_api')->user();

        if (!$customer && $request->filled('email')) {
            $customer = Customer::where('email', strtolower($request->email))->first();
        }

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'Customer account not found.'
            ], 404);
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'mobile' => 'required|string|max:25',
        ]);

        $customer->name = trim($request->name);
        $customer->mobile = trim($request->mobile);
        $customer->save();

        return response()->json([
            'success' => true,
            'message' => 'Profile updated successfully!',
            'user' => [
                'id' => (string) $customer->id,
                'name' => $customer->name,
                'email' => $customer->email,
                'mobile' => $customer->mobile,
            ]
        ]);
    }

    public function forgotPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
        ]);

        $email = strtolower(trim($request->email));
        $customer = Customer::where('email', $email)->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'No account registered with this email address.'
            ], 404);
        }

        return response()->json([
            'success' => true,
            'message' => 'Password reset verification approved. Please enter your new password.',
        ]);
    }

    public function resetPassword(Request $request)
    {
        $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string|min:6',
        ]);

        $email = strtolower(trim($request->email));
        $customer = Customer::where('email', $email)->first();

        if (!$customer) {
            return response()->json([
                'success' => false,
                'message' => 'No account registered with this email address.'
            ], 404);
        }

        $customer->password = Hash::make($request->password);
        $customer->save();

        return response()->json([
            'success' => true,
            'message' => 'Password reset successfully! You can now log in with your new password.'
        ]);
    }
}
