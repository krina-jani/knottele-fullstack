<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database with KNOTELLE boutique data.
     */
    public function run(): void
    {
        $this->call([
            AdminSeeder::class,
            SettingsSeeder::class,
            KnotelleStoreSeeder::class,
            MediaTaxonomySeeder::class,
            ReviewSeeder::class,
            DemoCustomerSeeder::class,
        ]);
    }
}
