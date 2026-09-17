<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\Order;

$orders = Order::all();
foreach ($orders as $o) {
    $correct = max(0, (float)$o->subtotal - (float)$o->discount_total + (float)$o->shipping_total);
    if (abs((float)$o->grand_total - $correct) > 0.01) {
        echo "Fixing Order #{$o->order_number}: grand_total {$o->grand_total} -> {$correct} (subtotal: {$o->subtotal}, discount: {$o->discount_total}, shipping: {$o->shipping_total})\n";
        $o->grand_total = $correct;
        $o->save();
    }
}
echo "Done checking " . count($orders) . " orders.\n";
