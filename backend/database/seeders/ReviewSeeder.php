<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Product;
use App\Models\Review;

class ReviewSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = Product::all();

        // Indian customer names
        $names = [
            'Anjali Patel',
            'Riya Shah',
            'Neha Mehta',
            'Pooja Vora',
            'Kajal Desai',
            'Sneha Joshi',
            'Bhavika Parmar',
            'Nidhi Trivedi',
            'Mitali Thakkar',
            'Komal Dave',
        ];

        // Beauty & skincare focused reviews
        $reviews = [
            'I can see visible improvement in my skin within a few weeks. Very gentle and effective.',
            'The texture is lightweight and suits my sensitive skin perfectly.',
            'Clinically formulated and it actually works. Highly recommended.',
            'My acne has reduced and skin feels much healthier now.',
            'Excellent product quality. No irritation and great results.',
            'Hair feels stronger and smoother after regular use.',
            'Perfect for daily use. You can feel the clinical quality.',
            'Very satisfied with the results. Will definitely repurchase.',
        ];

        foreach ($products as $product) {

            // Create exactly 3 reviews per product
            for ($i = 0; $i < 3; $i++) {
                Review::create([
                    'product_id' => $product->id,
                    'user_name' => $names[array_rand($names)],
                    'user_icon' => null,
                    'rating' => rand(4, 5), // Ensures avg rating ≥ 4
                    'review' => $reviews[array_rand($reviews)],
                    'status' => true,
                ]);
            }
        }
    }
}
