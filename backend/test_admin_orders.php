<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$query = \App\Models\Order::with(['customer', 'items', 'payments'])
    ->withCount(['items'])
    ->latest();

$orders = $query->paginate(10)
    ->through(function ($order) {
        return [
            'id' => $order->id,
            'order_number' => $order->order_number,
            'customer_name' => $order->customer->name ?? 'N/A',
            'customer_email' => $order->customer->email ?? 'N/A',
            'customer_mobile' => $order->shipping_address['phone'] ?? $order->customer->mobile ?? 'N/A',
            'date' => $order->created_at->format('Y-m-d'),
            'created_at' => $order->created_at,
            'items_count' => $order->items_count,
            'grand_total' => $order->grand_total,
            'status' => $order->status,
            'payment_status' => $order->payment_status,
            'payment_method' => match ($order->payment_method) {
                'cod' => 'Cash on Delivery',
                'online' => 'Online Payment',
                default => 'N/A',
            },

            'shipping_address' => $order->shipping_address ? implode(', ', array_filter($order->shipping_address)) : 'N/A',
            'tracking_number' => $order->shipments->first()->tracking_number ?? null,
        ];
    });

echo json_encode($orders->items(), JSON_PRETTY_PRINT);
