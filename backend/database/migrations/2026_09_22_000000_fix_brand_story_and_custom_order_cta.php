<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use App\Models\Media;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // 1. Fix Custom Order CTA typo and clear accidental test description
        $customOrderMedias = Media::where('section', 'custom_order')->get();
        foreach ($customOrderMedias as $m) {
            $m->cta_link = '/custom-order';
            $m->cta_text = 'Start a Custom Order';
            $m->tag_text = 'Bespoke Handcrafting';
            $m->title = 'Your Idea.';
            $m->subtitle = 'Our Yarn.';
            $m->description = "From custom color palettes and personalized initials to unique floral bouquets and character plushies — let's create something made especially for you.";
            $m->is_active = true;
            $m->save();
        }

        // Also fix any other media record where cta_link has /custom-orderrbtydsg or similar typos
        Media::where('cta_link', 'like', '%custom-orderrbtydsg%')
            ->orWhere('cta_link', 'like', '/custom-order%')
            ->where('cta_link', '!=', '/custom-order')
            ->where('cta_link', 'not like', '/custom-orders%')
            ->update(['cta_link' => '/custom-order']);

        // 2. Fix Brand Story media record
        $brandStoryMedias = Media::where('page', 'homepage')
            ->where('section', 'brand_story')
            ->get();

        if ($brandStoryMedias->isEmpty()) {
            Media::create([
                'page' => 'homepage',
                'section' => 'brand_story',
                'slot' => 'brand_story_desktop',
                'file_name' => 'brand_story_brand_story_desktop_1789984574_xk7I.png',
                'file_path' => 'images/homepage/brand_story_brand_story_desktop_1789984574_xk7I.png',
                'disk' => 'local',
                'mime_type' => 'image/png',
                'file_type' => 'image',
                'title' => 'Every Stitch',
                'subtitle' => 'Has a Story',
                'tag_text' => 'KNOTELLE Artisanal Crochet Craftsmanship',
                'description' => 'More than just crochet, we create memories, happiness and a little bit of magic.',
                'cta_text' => 'Read Our Story',
                'cta_link' => '/about',
                'is_active' => true,
                'uploaded_by' => 1,
                'uploader_type' => 'admin',
            ]);
        } else {
            foreach ($brandStoryMedias as $bs) {
                $bs->slot = 'brand_story_desktop';
                $bs->file_path = 'images/homepage/brand_story_brand_story_desktop_1789984574_xk7I.png';
                if ($bs->title === 'Brand Story Desktop Background' || empty($bs->title)) {
                    $bs->title = 'Every Stitch';
                }
                if (empty($bs->subtitle)) {
                    $bs->subtitle = 'Has a Story';
                }
                if (empty($bs->tag_text)) {
                    $bs->tag_text = 'KNOTELLE Artisanal Crochet Craftsmanship';
                }
                if (empty($bs->description)) {
                    $bs->description = 'More than just crochet, we create memories, happiness and a little bit of magic.';
                }
                $bs->cta_text = 'Read Our Story';
                $bs->cta_link = '/about';
                $bs->is_active = true;
                $bs->save();
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Non-destructive data repair
    }
};
