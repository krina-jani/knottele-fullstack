<?php
require __DIR__.'/vendor/autoload.php';
$app = require_once __DIR__.'/bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$variant = App\Models\ProductVariant::with('product.taxClass')->first();
if ($variant && $variant->product && $variant->product->taxClass) {
    echo "Tax Rate: " . $variant->product->taxClass->total_rate . "\n";
} else {
    echo "Missing relationship.\n";
}
