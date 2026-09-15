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

        $curatedReviews = [
            [
                'user_name' => 'Aanya Verma',
                'rating' => 5.0,
                'review' => 'The stitchwork is unbelievably neat and tight! Arrived in a lovely gift box with dried lavender petals. My sister loved it.'
            ],
            [
                'user_name' => 'Rhea Mukherjee',
                'rating' => 5.0,
                'review' => '100% pure milk cotton yarn, feels super soft and premium. Exactly as shown in the boutique photos!'
            ],
            [
                'user_name' => 'Tanvi Deshmukh',
                'rating' => 4.8,
                'review' => 'I keep this on my study desk and it brings warmth to my workspace every single day. Everlasting blossoms are pure magic.'
            ],
            [
                'user_name' => 'Isha Kapoor',
                'rating' => 5.0,
                'review' => 'Such a thoughtful handcrafted piece. The attention to detail in every single petal and loop is breathtaking.'
            ],
            [
                'user_name' => 'Pooja Vora',
                'rating' => 4.9,
                'review' => 'The color harmony is aesthetic and cottagecore. Sturdy craftsmanship with no loose yarn ends anywhere.'
            ],
            [
                'user_name' => 'Sneha Joshi',
                'rating' => 5.0,
                'review' => 'Came with a sweet handwritten care card. You can truly feel the love and time poured into every stitch. Highly recommend KNOTELLE!'
            ],
        ];

        foreach ($products as $product) {
            // Seed 2 curated reviews per product if none exist
            $sampleReviews = array_slice($curatedReviews, ($product->id % 3), 2);
            foreach ($sampleReviews as $item) {
                Review::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'user_name' => $item['user_name'],
                    ],
                    [
                        'rating' => $item['rating'],
                        'review' => $item['review'],
                        'user_icon' => null,
                        'status' => true,
                    ]
                );
            }
        }
    }
}
