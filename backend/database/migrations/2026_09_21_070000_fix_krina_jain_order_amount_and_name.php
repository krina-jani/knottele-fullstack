<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\Order;
use App\Models\Customer;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Fix order KN6ABOD2A76AA4D specifically
        $order = Order::where('order_number', 'KN6ABOD2A76AA4D')->first();
        if ($order) {
            $customer = Customer::where('email', 'krinajani10@gmail.com')->first();
            if ($customer) {
                $order->customer_id = $customer->id;
            }

            $shipping = $order->shipping_address;
            if (is_array($shipping)) {
                $shipping['name'] = 'krina jain';
                $shipping['email'] = 'krinajani10@gmail.com';
                $order->shipping_address = $shipping;
            }

            $billing = $order->billing_address;
            if (is_array($billing)) {
                $billing['name'] = 'krina jain';
                $billing['email'] = 'krinajani10@gmail.com';
                $order->billing_address = $billing;
            }

            $order->subtotal = 999.00;
            $order->discount_total = 99.00;
            $order->shipping_total = 0.00;
            $order->grand_total = 900.00;
            $order->save();
        }

        // 2. Fix any order where subtotal >= 999 and shipping_total was erroneously charged as 99
        Order::where('subtotal', '>=', 999)
            ->where('shipping_total', 99)
            ->get()
            ->each(function ($ord) {
                $ord->shipping_total = 0.00;
                $ord->grand_total = max(0, round((float) $ord->subtotal - (float) $ord->discount_total, 2));
                $ord->save();
            });

        // 3. Ensure any order with email krinajani10@gmail.com is linked to customer krina jain
        $krina = Customer::where('email', 'krinajani10@gmail.com')->first();
        if ($krina) {
            Order::where('shipping_address->email', 'krinajani10@gmail.com')
                ->whereNull('customer_id')
                ->update(['customer_id' => $krina->id]);
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Data fix migration
    }
};
