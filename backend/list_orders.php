<?php

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$orders = App\Models\Order::all();
foreach ($orders as $o) {
    echo "ID: {$o->id} | NUM: {$o->order_number} | EMAIL: " . ($o->shipping_address['email'] ?? 'N/A') . " | NAME: " . ($o->shipping_address['name'] ?? 'N/A') . " | STATUS: {$o->status} | PAY: {$o->payment_status} | TOTAL: {$o->grand_total}\n";
}
