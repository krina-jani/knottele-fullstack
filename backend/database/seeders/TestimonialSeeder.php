<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Testimonial;

class TestimonialSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $testimonials = [
            [
                'name' => 'Anjali Mehta',
                'designation' => 'Working Professional',
                'message' => 'I noticed visible improvement in my skin texture within a few weeks. The products feel gentle yet very effective. Truly clinical-grade skincare.',
                'rating' => 5,
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Riya Patel',
                'designation' => 'College Student',
                'message' => 'I struggled with acne for years, and Nuts and Nutrition’s products really helped calm my skin. No harsh reactions and great results.',
                'rating' => 5,
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Neha Shah',
                'designation' => 'Homemaker',
                'message' => 'My hair fall has reduced significantly after using the haircare range. You can tell these products are made with real clinical experience.',
                'rating' => 5,
                'image' => null,
                'is_active' => true,
            ],
            [
                'name' => 'Pooja Vora',
                'designation' => 'Salon Owner',
                'message' => 'As a beauty professional, I am very particular about ingredients. These products are safe, effective, and results-driven. Highly recommended.',
                'rating' => 4,
                'image' => null,
                'is_active' => true,
            ],
        ];

        foreach ($testimonials as $testimonial) {
            Testimonial::create($testimonial);
        }
    }
}
