<?php

namespace Tests\Unit;

use App\Models\BlogCategory;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class BlogCategoryTranslationTest extends TestCase
{
    use RefreshDatabase;

    public function test_get_translation_uses_eager_loaded_collection_without_extra_queries(): void
    {
        $category = BlogCategory::create();
        $category->translations()->create([
            'locale' => 'fa',
            'name' => 'مهاجرت',
            'slug' => 'immigration-fa',
        ]);
        $category->translations()->create([
            'locale' => 'en',
            'name' => 'Immigration',
            'slug' => 'immigration-en',
        ]);

        $loaded = BlogCategory::with('translations')->findOrFail($category->id);

        DB::flushQueryLog();
        DB::enableQueryLog();

        $fa = $loaded->getTranslation('fa');
        $en = $loaded->getTranslation('en');

        $queryCount = count(DB::getQueryLog());
        DB::disableQueryLog();

        $this->assertSame('مهاجرت', $fa?->name);
        $this->assertSame('Immigration', $en?->name);
        $this->assertSame(0, $queryCount, 'Eager-loaded getTranslation must not hit the database');
    }
}
