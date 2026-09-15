<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Media;
use App\Models\Category;

class MediaTaxonomySeeder extends Seeder
{
    public function run(): void
    {
        // 1. Hero slides
        Media::where('file_path', 'like', '%hero-enhanced.jpg%')->update([
            'page' => 'homepage',
            'section' => 'hero',
            'slot' => 'hero_slide_1_desktop',
            'device' => 'desktop',
            'title' => 'Little Stitches Big Happiness',
            'subtitle' => 'Handmade crochet products that add warmth to your everyday life.',
            'cta_text' => 'Shop All Collections',
            'cta_link' => '/shop',
            'tag_text' => 'Good Things Are Handmade',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        Media::where('file_path', 'like', '%2image.png%')->update([
            'page' => 'homepage',
            'section' => 'hero',
            'slot' => 'hero_slide_2_desktop',
            'device' => 'desktop',
            'title' => 'Everyday Elegance Handcrafted Bags',
            'subtitle' => 'Artisanal granny square bags & wearable creations woven with love.',
            'cta_text' => 'Explore Bags',
            'cta_link' => '/category/bags',
            'tag_text' => 'Every Stitch Has A Story',
            'sort_order' => 2,
            'is_active' => true,
        ]);

        Media::where('file_path', 'like', '%3image.png%')->update([
            'page' => 'homepage',
            'section' => 'hero',
            'slot' => 'hero_slide_3_desktop',
            'device' => 'desktop',
            'title' => 'Everlasting Blooms Made With Love',
            'subtitle' => 'Bespoke everlasting flower bouquets crafted to brighten every moment.',
            'cta_text' => 'Shop Flowers',
            'cta_link' => '/category/flower',
            'tag_text' => 'Crafted Just For You',
            'sort_order' => 3,
            'is_active' => true,
        ]);

        Media::where('file_path', 'like', '%4image.png%')->update([
            'page' => 'homepage',
            'section' => 'hero',
            'slot' => 'hero_slide_4_desktop',
            'device' => 'desktop',
            'title' => 'Bespoke Creations Custom Crafted',
            'subtitle' => 'Personalized crochet treasures tailored specially for your memorable moments.',
            'cta_text' => 'Request Custom Order',
            'cta_link' => '/custom-order',
            'tag_text' => 'Pure Artisan Warmth',
            'sort_order' => 4,
            'is_active' => true,
        ]);

        // 2. Custom Crochet Banner
        Media::where('file_path', 'like', '%middleimg.png%')->update([
            'page' => 'homepage',
            'section' => 'custom_crochet',
            'slot' => 'custom_crochet_desktop',
            'device' => 'desktop',
            'title' => 'Custom Crochet',
            'subtitle' => 'Just for You',
            'description' => 'Your imagination, our yarn. Let\'s create something special together.',
            'cta_text' => 'Request Your Custom Order',
            'cta_link' => '/custom-order',
            'tag_text' => 'Turn Your Ideas Into Handmade Reality',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // 3. Footer Artwork
        Media::where('file_path', 'like', '%footer.png%')->update([
            'page' => 'homepage',
            'section' => 'footer',
            'slot' => 'footer_bg',
            'device' => 'all',
            'title' => 'Footer Background Artwork',
            'subtitle' => 'Made with ♡ for a kinder, cozier world.',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // 4. Global Assets
        Media::where('file_path', 'like', '%Logo_1.png%')->update([
            'page' => 'global',
            'section' => 'global_assets',
            'slot' => 'main_logo',
            'device' => 'all',
            'title' => 'Main Boutique Crown Logo',
            'sort_order' => 1,
            'is_active' => true,
        ]);

        // 5. Category Images
        foreach (Category::all() as $cat) {
            if ($cat->image_id) {
                Media::where('id', $cat->image_id)->update([
                    'page' => 'homepage',
                    'section' => 'categories',
                    'slot' => 'category_' . $cat->slug,
                    'device' => 'all',
                    'title' => $cat->name . ' Category Image',
                    'sort_order' => $cat->sort_order ?: 1,
                    'is_active' => (bool)$cat->status,
                ]);
            }
        }

        // 6. Dynamic Blog & Video Reels Section Settings
        Media::updateOrCreate(
            [
                'page' => 'homepage',
                'section' => 'blog_reels',
                'slot' => 'section_settings',
            ],
            [
                'file_name' => 'section_settings.json',
                'file_path' => 'media/blog_reels/section_settings.json',
                'disk' => 'local',
                'mime_type' => 'application/json',
                'file_type' => 'document',
                'title' => 'Behind the Stitches',
                'subtitle' => 'Watch our artisans hand-craft each creation, styling guides, and cozy studio ASMR unboxings.',
                'tag_text' => 'Studio Journal & Video Reels',
                'cta_text' => 'Follow @knotelleindia',
                'cta_link' => 'https://instagram.com/knotelleindia',
                'sort_order' => 0,
                'is_active' => true,
            ]
        );

        // 7. Dynamic Blog & Video Reels Sample Items
        $reelItems = [
            [
                'slot' => 'blog_reel_1',
                'title' => 'Slow Mornings: Assembling a 9-Rose Bouquet',
                'subtitle' => 'Satisfying Yarn Sounds',
                'description' => 'Listen closely to the gentle rustle of matte paper, stem winding, and the final wax stamp seal in our Bengaluru atelier.',
                'content_type' => 'reel',
                'category_name' => 'Studio ASMR',
                'video_url' => 'https://assets.mixkit.co/videos/preview/mixkit-hands-of-a-woman-crocheting-with-pink-yarn-42845-large.mp4',
                'desktop_image_url' => 'https://images.unsplash.com/photo-1584992236310-6edddc08acff?q=80&w=800&auto=format&fit=crop',
                'duration' => '00:42',
                'audio_name' => 'Original Studio Audio',
                'likes_count' => 14200,
                'comments_count' => 380,
                'views_count' => 89400,
                'is_featured' => true,
                'sort_order' => 1,
            ],
            [
                'slot' => 'blog_reel_2',
                'title' => '3 Ways to Style Your Botanical Granny Square Bag',
                'subtitle' => 'Casual & Formal Looks',
                'description' => 'From Sunday brunch to college lectures, see how our handcrafted granny square tote pairs with neutral linens and oversized knits.',
                'content_type' => 'reel',
                'category_name' => 'Style Guide',
                'video_url' => 'https://assets.mixkit.co/videos/preview/mixkit-close-up-of-hands-knitting-with-white-wool-42843-large.mp4',
                'desktop_image_url' => 'https://images.unsplash.com/photo-1590736969955-71cc94801759?q=80&w=800&auto=format&fit=crop',
                'duration' => '00:58',
                'audio_name' => 'Aesthetic Acoustic Guitar',
                'likes_count' => 8900,
                'comments_count' => 195,
                'views_count' => 52100,
                'is_featured' => false,
                'sort_order' => 2,
            ],
            [
                'slot' => 'blog_reel_3',
                'title' => 'Color Harmony: Dyeing & Selecting Yarn Batches',
                'subtitle' => 'Artisan Workshop',
                'description' => 'Ever wondered how we achieve our signature muted pastel palettes? Come behind the scenes with lead artisan Krina in our sunny studio.',
                'content_type' => 'reel',
                'category_name' => 'Behind the Scenes',
                'video_url' => 'https://assets.mixkit.co/videos/preview/mixkit-woman-working-on-a-crochet-project-42844-large.mp4',
                'desktop_image_url' => 'https://images.unsplash.com/photo-1615247001958-f4bc92fa6a4a?q=80&w=800&auto=format&fit=crop',
                'duration' => '01:15',
                'audio_name' => 'Lo-Fi Cozy Beats',
                'likes_count' => 23400,
                'comments_count' => 612,
                'views_count' => 134000,
                'is_featured' => true,
                'sort_order' => 3,
            ],
            [
                'slot' => 'blog_reel_4',
                'title' => 'Mastering the Magic Ring: Beginner to Pro',
                'subtitle' => 'Quick Tutorial',
                'description' => 'The foundation of all amigurumi and floral petals made effortless. Follow these slow-motion tips to never have loose loops again.',
                'content_type' => 'reel',
                'category_name' => 'Masterclass',
                'video_url' => 'https://assets.mixkit.co/videos/preview/mixkit-hands-crocheting-with-yellow-yarn-42842-large.mp4',
                'desktop_image_url' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=800&auto=format&fit=crop',
                'duration' => '02:04',
                'audio_name' => 'Relaxing Coffee Shop Jazz',
                'likes_count' => 31200,
                'comments_count' => 940,
                'views_count' => 210500,
                'is_featured' => true,
                'sort_order' => 4,
            ],
        ];

        foreach ($reelItems as $reel) {
            Media::updateOrCreate(
                [
                    'page' => 'homepage',
                    'section' => 'blog_reels',
                    'slot' => $reel['slot'],
                ],
                [
                    'file_name' => $reel['slot'] . '.jpg',
                    'file_path' => $reel['desktop_image_url'],
                    'disk' => 'local',
                    'mime_type' => 'image/jpeg',
                    'file_type' => 'video',
                    'title' => $reel['title'],
                    'subtitle' => $reel['subtitle'],
                    'description' => $reel['description'],
                    'content_type' => $reel['content_type'],
                    'category_name' => $reel['category_name'],
                    'video_url' => $reel['video_url'],
                    'duration' => $reel['duration'],
                    'audio_name' => $reel['audio_name'],
                    'likes_count' => $reel['likes_count'],
                    'comments_count' => $reel['comments_count'],
                    'views_count' => $reel['views_count'],
                    'is_featured' => $reel['is_featured'],
                    'sort_order' => $reel['sort_order'],
                    'is_active' => true,
                ]
            );
        }

        // 8. Dynamic About Page Story / Atelier Seeding
        Media::updateOrCreate(
            [
                'page' => 'about',
                'section' => 'about_story',
                'slot' => 'story_hero',
            ],
            [
                'file_name' => 'about_story_hero.jpg',
                'file_path' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=1000&auto=format&fit=crop',
                'disk' => 'local',
                'mime_type' => 'image/jpeg',
                'file_type' => 'image',
                'title' => 'Every Loop Tells a Story',
                'tag_text' => 'The KNOTELLE Story',
                'subtitle' => 'Handcrafted slow-made warmth from Bengaluru',
                'description' => 'In a world flooded with disposable factory goods, KNOTELLE was born from a yearning for slow-made warmth. What began as a tiny home workshop in Bengaluru has grown into a vibrant collective of women artisans who share a deep love for yarn, color harmony, and delicate floral silhouettes.',
                'cta_text' => 'Request a Custom Creation',
                'cta_link' => '/custom-order',
                'alt_text' => 'Artisan stitching crochet with wooden hook',
                'sort_order' => 1,
                'is_active' => true,
                'metadata' => [
                    'paragraph_2' => 'When you order a bouquet of crochet roses, a customized bunny keychain, or a granny square tote, you are not simply purchasing an object. You are welcoming hours of human patience, intention, and joy into your home.',
                    'floating_badge_title' => '100% Handcrafted',
                    'floating_badge_subtitle' => 'Never mass machine produced',
                    'floating_badge_icon' => 'Heart',
                    'floating_badge_active' => true,
                    'cta_visible' => true,
                ],
            ]
        );

        // 9. Dynamic Craft Pillars Section Header Seeding
        Media::updateOrCreate(
            [
                'page' => 'about',
                'section' => 'craft_pillars',
                'slot' => 'section_settings',
            ],
            [
                'file_name' => 'craft_pillars_settings.png',
                'file_path' => 'images/logo/Logo_1.png',
                'disk' => 'local',
                'mime_type' => 'image/png',
                'file_type' => 'image',
                'title' => 'Our Craft Pillars',
                'subtitle' => 'Guiding principles behind every stitch we make.',
                'description' => 'Guiding principles behind every stitch we make.',
                'tag_text' => 'Artisan Standards',
                'sort_order' => 0,
                'is_active' => true,
            ]
        );

        // 10. Dynamic Craft Pillars Items Seeding
        $pillarItems = [
            [
                'slot' => 'pillar_natural_materials',
                'title' => 'Natural Materials',
                'description' => 'We use 100% pure milk cotton and mercerized organic fibers that are gentle on skin and kind to mother nature.',
                'icon_name' => 'Leaf',
                'icon_type' => 'preset',
                'sort_order' => 1,
            ],
            [
                'slot' => 'pillar_bespoke_atelier',
                'title' => 'Bespoke Atelier',
                'description' => 'No two requests are alike. We collaborate with you on colors, sizing, and details to make every piece unique.',
                'icon_name' => 'Sparkles',
                'icon_type' => 'preset',
                'sort_order' => 2,
            ],
            [
                'slot' => 'pillar_empowering_artisans',
                'title' => 'Empowering Artisans',
                'description' => 'We provide fair wages, flexible hours, and creative dignity to our women-led artisan crafting community in India.',
                'icon_name' => 'Heart',
                'icon_type' => 'preset',
                'sort_order' => 3,
            ],
        ];

        foreach ($pillarItems as $pillar) {
            Media::updateOrCreate(
                [
                    'page' => 'about',
                    'section' => 'craft_pillars',
                    'slot' => $pillar['slot'],
                ],
                [
                    'file_name' => 'icon_' . strtolower($pillar['icon_name']) . '.png',
                    'file_path' => 'icon_' . strtolower($pillar['icon_name']),
                    'disk' => 'local',
                    'mime_type' => 'image/png',
                    'file_type' => 'image',
                    'title' => $pillar['title'],
                    'description' => $pillar['description'],
                    'tag_text' => $pillar['icon_name'],
                    'sort_order' => $pillar['sort_order'],
                    'is_active' => true,
                    'metadata' => [
                        'icon_name' => $pillar['icon_name'],
                        'icon_type' => $pillar['icon_type'],
                    ],
                ]
            );
        }

        // 11. Dynamic Contact Intro / Hero Seeding
        Media::updateOrCreate(
            [
                'page' => 'contact',
                'section' => 'contact_intro',
                'slot' => 'intro_banner',
            ],
            [
                'file_name' => 'contact_hero.jpg',
                'file_path' => 'https://images.unsplash.com/photo-1544816155-12df9643f363?q=80&w=1200&auto=format&fit=crop',
                'disk' => 'local',
                'mime_type' => 'image/jpeg',
                'file_type' => 'image',
                'title' => "Let's Connect",
                'tag_text' => "Let's Connect",
                'subtitle' => "Have a question about a product, custom order, or collaboration? We'd love to hear from you.",
                'description' => "We're here to help bring your handcrafted crochet dreams to life. Reach out directly or fill out our message form below.",
                'cta_text' => 'Send Us a Message',
                'cta_link' => '#contact-form',
                'alt_text' => 'KNOTELLE Artisan Studio Contact',
                'sort_order' => 1,
                'is_active' => true,
            ]
        );

        // 12. Dynamic Contact Information Section Header Seeding
        Media::updateOrCreate(
            [
                'page' => 'contact',
                'section' => 'contact_info',
                'slot' => 'section_settings',
            ],
            [
                'file_name' => 'contact_info_settings.json',
                'file_path' => 'media/contact/contact_info_settings.json',
                'disk' => 'local',
                'mime_type' => 'application/json',
                'file_type' => 'document',
                'title' => 'KNOTELLE Studio',
                'tag_text' => 'Atelier Studio',
                'subtitle' => 'Handmade with love in Bengaluru, India',
                'sort_order' => 0,
                'is_active' => true,
                'metadata' => [
                    'custom_order_box_title' => 'Looking for Custom Orders?',
                    'custom_order_box_text' => 'Have a specific design, color palette, or bouquet arrangement in mind? Request a bespoke piece directly.',
                    'custom_order_box_link' => '/custom-order',
                    'custom_order_box_active' => true,
                ],
            ]
        );

        // 13. Dynamic Contact Information Items Seeding
        $contactDetails = [
            [
                'slot' => 'info_address',
                'tag_text' => 'MapPin',
                'title' => 'Visit Our Studio',
                'description' => 'KNOTELLE Studio, 12th Main Road, Indiranagar',
                'address_line_2' => 'Bengaluru, Karnataka 560038, India',
                'cta_link' => 'https://maps.google.com/?q=Indiranagar+Bengaluru',
                'sort_order' => 1,
            ],
            [
                'slot' => 'info_phone',
                'tag_text' => 'Phone',
                'title' => 'Call / WhatsApp Us',
                'description' => '+91 98765 43210',
                'address_line_2' => 'Mon – Sat, 10:00 AM – 7:00 PM IST',
                'cta_link' => 'tel:+919876543210',
                'sort_order' => 2,
            ],
            [
                'slot' => 'info_email',
                'tag_text' => 'Mail',
                'title' => 'Write to Us',
                'description' => 'hello@knotelle.com',
                'address_line_2' => 'We usually reply within 24 hours',
                'cta_link' => 'mailto:hello@knotelle.com',
                'sort_order' => 3,
            ],
            [
                'slot' => 'info_hours',
                'tag_text' => 'Clock',
                'title' => 'Studio Hours',
                'description' => 'Monday – Saturday: 10:00 AM – 7:00 PM',
                'address_line_2' => 'Sunday: Closed (Crafting in silence)',
                'cta_link' => '',
                'sort_order' => 4,
            ],
        ];

        foreach ($contactDetails as $detail) {
            Media::updateOrCreate(
                [
                    'page' => 'contact',
                    'section' => 'contact_info',
                    'slot' => $detail['slot'],
                ],
                [
                    'file_name' => $detail['slot'] . '.json',
                    'file_path' => 'icon_' . strtolower($detail['tag_text']),
                    'disk' => 'local',
                    'mime_type' => 'application/json',
                    'file_type' => 'document',
                    'title' => $detail['title'],
                    'description' => $detail['description'],
                    'tag_text' => $detail['tag_text'],
                    'cta_link' => $detail['cta_link'],
                    'sort_order' => $detail['sort_order'],
                    'is_active' => true,
                    'metadata' => [
                        'icon_name' => $detail['tag_text'],
                        'address_line_2' => $detail['address_line_2'],
                    ],
                ]
            );
        }

        // 14. Dynamic Send Us a Message Form Settings Seeding
        Media::updateOrCreate(
            [
                'page' => 'contact',
                'section' => 'contact_form',
                'slot' => 'form_settings',
            ],
            [
                'file_name' => 'contact_form_settings.json',
                'file_path' => 'media/contact/contact_form_settings.json',
                'disk' => 'local',
                'mime_type' => 'application/json',
                'file_type' => 'document',
                'title' => 'Send Us a Message',
                'tag_text' => 'Get In Touch',
                'subtitle' => 'Fill in your details and our team will get back to you promptly.',
                'cta_text' => 'Send Message',
                'sort_order' => 0,
                'is_active' => true,
                'metadata' => [
                    'success_title' => 'Message Sent!',
                    'success_message' => 'Thank you! Your message has been sent successfully. We will get back to you shortly.',
                    'error_message' => 'Something went wrong while sending your message. Please check the form and try again.',
                    'fields' => [
                        ['key' => 'name', 'label' => 'Your Name', 'placeholder' => 'Enter your full name', 'required' => true, 'is_active' => true],
                        ['key' => 'email', 'label' => 'Email Address', 'placeholder' => 'Enter your email address', 'required' => true, 'is_active' => true],
                        ['key' => 'phone', 'label' => 'Phone Number', 'placeholder' => 'Enter your 10-digit phone number (optional)', 'required' => false, 'is_active' => true],
                        ['key' => 'subject', 'label' => 'Subject', 'placeholder' => 'What is this regarding?', 'required' => false, 'is_active' => true],
                        ['key' => 'message', 'label' => 'Your Message', 'placeholder' => 'Tell us how we can help you...', 'required' => true, 'is_active' => true],
                    ],
                ],
            ]
        );

        // 15. Dynamic Frequently Asked Questions (FAQ) Header Seeding
        Media::updateOrCreate(
            [
                'page' => 'contact',
                'section' => 'contact_faqs',
                'slot' => 'section_settings',
            ],
            [
                'file_name' => 'contact_faqs_settings.json',
                'file_path' => 'media/contact/contact_faqs_settings.json',
                'disk' => 'local',
                'mime_type' => 'application/json',
                'file_type' => 'document',
                'title' => 'Frequently Asked Questions',
                'tag_text' => 'Help & Support',
                'subtitle' => 'Quick answers about our handmade creations, custom orders, and delivery.',
                'sort_order' => 0,
                'is_active' => true,
            ]
        );

        // 16. Dynamic Frequently Asked Questions Items Seeding
        $faqItems = [
            [
                'slot' => 'faq_custom_orders',
                'title' => 'How long does a custom order take?',
                'description' => 'Custom orders usually take 7–14 working days depending on complexity and yarn availability. Once dispatched, courier delivery takes 3–5 business days.',
                'sort_order' => 1,
            ],
            [
                'slot' => 'faq_shipping',
                'title' => 'Do you ship across India?',
                'description' => 'Yes! We offer Pan-India shipping with trusted courier partners. Express delivery options and tracked shipping are provided for all orders.',
                'sort_order' => 2,
            ],
            [
                'slot' => 'faq_care',
                'title' => 'How do I care for my crochet items?',
                'description' => 'Hand-wash gently in cold water using mild liquid detergent. Lay flat on a clean dry towel in shade to dry. Do not machine wash, bleach, or wring.',
                'sort_order' => 3,
            ],
            [
                'slot' => 'faq_bouquet',
                'title' => 'Can I customize the colors of a flower bouquet?',
                'description' => 'Absolutely! Every everlasting bouquet can be customized with your choice of flower types, stem count, color shades, and personalized gift wrapping notes.',
                'sort_order' => 4,
            ],
        ];

        foreach ($faqItems as $faq) {
            Media::updateOrCreate(
                [
                    'page' => 'contact',
                    'section' => 'contact_faqs',
                    'slot' => $faq['slot'],
                ],
                [
                    'file_name' => $faq['slot'] . '.json',
                    'file_path' => 'faq_item',
                    'disk' => 'local',
                    'mime_type' => 'application/json',
                    'file_type' => 'document',
                    'title' => $faq['title'],
                    'description' => $faq['description'],
                    'sort_order' => $faq['sort_order'],
                    'is_active' => true,
                ]
            );
        }

        // 17. Dynamic Footer Settings Seeding
        Media::updateOrCreate(
            [
                'page' => 'homepage',
                'section' => 'footer',
                'slot' => 'section_settings',
            ],
            [
                'file_name' => 'footer_settings.json',
                'file_path' => 'media/footer/footer_settings.json',
                'disk' => 'local',
                'mime_type' => 'application/json',
                'file_type' => 'document',
                'title' => 'KNOTELLE Boutique Footer',
                'subtitle' => 'Made with ♡ for a kinder, cozier world.',
                'tag_text' => 'Boutique Footer',
                'sort_order' => 0,
                'is_active' => true,
                'metadata' => [
                    'col1_title' => 'Quick Links',
                    'col1_links' => [
                        ['label' => 'Home', 'url' => '/', 'is_active' => true],
                        ['label' => 'Shop', 'url' => '/shop', 'is_active' => true],
                        ['label' => 'Custom Order', 'url' => '/custom-order', 'is_active' => true],
                        ['label' => 'About', 'url' => '/about', 'is_active' => true],
                        ['label' => 'Contact', 'url' => '/contact', 'is_active' => true],
                    ],
                    'col2_title' => 'Help',
                    'col2_links' => [
                        ['label' => 'Shipping Policy', 'url' => '/contact', 'is_active' => true],
                        ['label' => 'Return & Refund', 'url' => '/contact', 'is_active' => true],
                        ['label' => 'FAQ', 'url' => '/contact', 'is_active' => true],
                        ['label' => 'Track Order', 'url' => '/account/orders', 'is_active' => true],
                    ],
                    'col3_title' => 'Contact',
                    'contact_phone' => '+91 97730 39243',
                    'contact_phone_link' => 'tel:+919773039243',
                    'contact_email' => 'support@knotelle.in',
                    'contact_email_link' => 'mailto:support@knotelle.in',
                    'contact_address' => 'India',
                    'contact_address_link' => '',
                    'instagram_url' => 'https://instagram.com/knotelleindia',
                    'instagram_active' => true,
                    'facebook_url' => 'https://facebook.com/knotelleindia',
                    'facebook_active' => true,
                    'pinterest_url' => 'https://pinterest.com/knotelleindia',
                    'pinterest_active' => true,
                    'youtube_url' => 'https://youtube.com/@knotelleindia',
                    'youtube_active' => true,
                    'copyright_text' => '© {year} Knotelle. All rights reserved.',
                    'heart_tagline' => 'Made with ♡ for a kinder, cozier world.',
                ],
            ]
        );

        // 18. Dynamic Navbar Settings Seeding
        Media::updateOrCreate(
            [
                'page' => 'global',
                'section' => 'navbar',
                'slot' => 'navbar_settings',
            ],
            [
                'file_name' => 'navbar_settings.json',
                'file_path' => 'media/navbar/navbar_settings.json',
                'disk' => 'local',
                'mime_type' => 'application/json',
                'file_type' => 'document',
                'title' => 'Navbar Navigation & Announcement',
                'subtitle' => 'Global website header navigation and banner',
                'tag_text' => 'Main Navigation',
                'sort_order' => 0,
                'is_active' => true,
                'metadata' => [
                    'announcement_text' => '✨ Free Pan-India Delivery on all Orders above ₹999',
                    'announcement_link' => '/shop',
                    'announcement_active' => false,
                    'nav_links' => [
                        ['name' => 'Home', 'href' => '/', 'is_highlighted' => false, 'is_active' => true, 'sort_order' => 1],
                        ['name' => 'Shop', 'href' => '/shop', 'is_highlighted' => false, 'is_active' => true, 'sort_order' => 2],
                        ['name' => 'Custom Order', 'href' => '/custom-order', 'is_highlighted' => true, 'is_active' => true, 'sort_order' => 3],
                        ['name' => 'About', 'href' => '/about', 'is_highlighted' => false, 'is_active' => true, 'sort_order' => 4],
                        ['name' => 'Contact', 'href' => '/contact', 'is_highlighted' => false, 'is_active' => true, 'sort_order' => 5],
                    ],
                    'show_search' => true,
                    'show_wishlist' => true,
                    'show_account' => true,
                    'show_cart' => true,
                ],
            ]
        );
    }
}
