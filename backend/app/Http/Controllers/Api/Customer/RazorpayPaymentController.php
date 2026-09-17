<?php

namespace App\Http\Controllers\Api\Customer;

use App\Http\Controllers\Controller;
use App\Services\Customer\RazorpayService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class RazorpayPaymentController extends Controller
{
    protected ?RazorpayService $razorpayService = null;

    public function __construct()
    {
        try {
            $this->razorpayService = app(RazorpayService::class);
        } catch (\Exception $e) {
            Log::warning('RazorpayService initialization warning: ' . $e->getMessage());
        }
    }

    /**
     * Create a Razorpay Order for Next.js Checkout.
     */
    public function createOrder(Request $request)
    {
        $request->validate([
            'amount' => 'required|numeric|gt:0',
        ]);

        try {
            $amountInPaise = (int) round($request->amount * 100);

            if (!$this->razorpayService) {
                $this->razorpayService = new RazorpayService();
            }

            $result = $this->razorpayService->createOrderByAmount($amountInPaise);

            if (!$result['success']) {
                return response()->json($result, 400);
            }

            return response()->json($result, 200);

        } catch (\Exception $e) {
            Log::error('Razorpay order creation endpoint error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Failed to initialize payment: ' . $e->getMessage(),
            ], 500);
        }
    }

    /**
     * Verify Razorpay payment signature before creating/confirming order.
     */
    public function verifyPayment(Request $request)
    {
        $request->validate([
            'razorpay_payment_id' => 'required|string',
            'razorpay_order_id' => 'required|string',
            'razorpay_signature' => 'required|string',
        ]);

        try {
            if (!$this->razorpayService) {
                $this->razorpayService = new RazorpayService();
            }

            $result = $this->razorpayService->verifyPayment(
                $request->razorpay_payment_id,
                $request->razorpay_order_id,
                $request->razorpay_signature
            );

            if (!$result['success']) {
                return response()->json($result, 400);
            }

            return response()->json($result, 200);

        } catch (\Exception $e) {
            Log::error('Razorpay payment verification endpoint error: ' . $e->getMessage());
            return response()->json([
                'success' => false,
                'message' => 'Payment signature verification failed: ' . $e->getMessage(),
            ], 400);
        }
    }
}
