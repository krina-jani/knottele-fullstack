<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

$offers = \App\Models\Offer::active()->get();
echo "Active scope:\n";
echo json_encode($offers, JSON_PRETTY_PRINT);

echo "\n\nQuery with additional where:\n";
$offers2 = \App\Models\Offer::active()
                ->where('status', true)
                ->where(function($q) {
                    $q->where(function($query) {
                        $query->whereNotNull('discount_value')
                              ->where('discount_value', '>', 0);
                    })
                    ->orWhereIn('offer_type', ['bogo', 'buy_x_get_y', 'free_shipping']);
                })->get();
echo json_encode($offers2, JSON_PRETTY_PRINT);
