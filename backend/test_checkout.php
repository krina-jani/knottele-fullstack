<?php
\ = Illuminate\Http\Request::create('/api/customer/orders', 'POST', [
    'items' => [
        [
            'variant_id' => \App\Models\ProductVariant::first()->id,
            'quantity' => 1
        ]
    ],
    'shipping_address' => [
        'name' => 'Test User',
        'email' => 'test@test.com',
        'phone' => '1234567890',
        'address_line_1' => '123 Test St',
        'city' => 'Test City',
        'pin_code' => '123456'
    ],
    'payment_method' => 'cod'
]);
\ = app()->make('App\Http\Controllers\Api\Customer\OrderController')->store(\);
echo \->getContent();

