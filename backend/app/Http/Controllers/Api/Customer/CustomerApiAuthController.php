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
            'email' => 'required|email',
            'password' => 'required'
        ]);

        $customer = Customer::where('email', $request->email)->first();

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
            return response()->json(['message' => 'Invalid email or password.'], 401);
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
            'mobile' => 'nullable|string|max:15',
            'password' => 'required|string|min:6',
        ]);

        $customer = Customer::create([
            'name' => $request->name,
            'email' => $request->email,
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
}
