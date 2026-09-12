<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

$offer = \App\Models\Offer::find(1);
$offer->discount_value = 20.00;
$offer->save();

echo "Offer updated successfully!";
