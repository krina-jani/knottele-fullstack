<?php echo \App\Models\Order::count() . " orders in DB. Latest order: " . json_encode(\App\Models\Order::latest()->first()->toArray());
