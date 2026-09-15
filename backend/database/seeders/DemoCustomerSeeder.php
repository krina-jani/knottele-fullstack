<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use App\Models\Customer;

class DemoCustomerSeeder extends Seeder
{
    public function run(): void
    {
        Customer::updateOrCreate(
            ['email' => 'user@example.com'],
            [
                'name' => 'Demo User',
                'mobile' => '9876543210',
                'password' => Hash::make('password123'),
                'status' => 1,
                'email_verified_at' => now(),
                'mobile_verified_at' => now(),
            ]
        );

        $this->command->info('Demo customer seeded successfully (user@example.com / password123).');
    }
}