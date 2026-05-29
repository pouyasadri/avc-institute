<?php

namespace Database\Seeders;

use App\Models\BlogCategory;
use App\Models\BlogCategoryTranslation;
use Illuminate\Database\Seeder;

class BlogCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'en' => ['name' => 'Immigration & Visas', 'slug' => 'immigration-visas-france'],
                'fr' => ['name' => 'Immigration et Visas', 'slug' => 'immigration-visas-france'],
                'fa' => ['name' => 'مهاجرت و ویزا', 'slug' => 'immigration-visa-france-farsi'],
            ],
            [
                'en' => ['name' => 'Education & Study', 'slug' => 'study-in-france'],
                'fr' => ['name' => 'Études et Formation', 'slug' => 'etudes-formation-france'],
                'fa' => ['name' => 'تحصیل و آموزش', 'slug' => 'study-education-france-farsi'],
            ],
            [
                'en' => ['name' => 'Work & Business', 'slug' => 'work-business-france'],
                'fr' => ['name' => 'Travail et Affaires', 'slug' => 'travail-affaires-france'],
                'fa' => ['name' => 'کار و تجارت', 'slug' => 'work-business-france-farsi'],
            ],
            [
                'en' => ['name' => 'Life in France', 'slug' => 'life-in-france-guide'],
                'fr' => ['name' => 'Vie en France', 'slug' => 'vie-en-france-guide'],
                'fa' => ['name' => 'زندگی در فرانسه', 'slug' => 'life-in-france-guide-farsi'],
            ],
            [
                'en' => ['name' => 'Tourism & Culture', 'slug' => 'tourism-culture-france'],
                'fr' => ['name' => 'Tourisme et Culture', 'slug' => 'tourisme-culture-france'],
                'fa' => ['name' => 'گردشگری و فرهنگ', 'slug' => 'tourism-culture-france-farsi'],
            ],
            [
                'en' => ['name' => 'Language Learning', 'slug' => 'learn-french-language'],
                'fr' => ['name' => 'Apprentissage du Français', 'slug' => 'apprendre-francais'],
                'fa' => ['name' => 'یادگیری زبان فرانسه', 'slug' => 'learn-french-farsi'],
            ],
        ];

        foreach ($categories as $catData) {
            // Create Category (Parent) - Assuming we verify by the English slug or similar,
            // but here we don't have a unique key on the parent itself easily,
            // so we might need to check if a translation exists or just create if strictly needed.
            // A better approach for multi-language is to find by one of the unique slugs.

            // Let's try to find by the English slug if possible, or just skip if exists.
            // However, the parent `BlogCategory` has no unique fields other than ID (auto-inc) and likely timestamps.
            // Validation is usually done via translations.

            // Strategy: Check if a category with the English slug exists.
            $englishSlug = $catData['en']['slug'];
            $existing = BlogCategoryTranslation::where('slug', $englishSlug)->where('locale', 'en')->first();

            if ($existing) {
                $category = $existing->category;
            } else {
                $category = BlogCategory::create();
            }

            // Create or Update Translations
            foreach ($catData as $locale => $data) {
                $category->translations()->updateOrCreate(
                    ['locale' => $locale],
                    ['name' => $data['name'], 'slug' => $data['slug']]
                );
            }
        }
    }
}
