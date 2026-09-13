<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogPostTranslation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PersianBlogCrossLinkingAndGeoEnrichmentTest extends TestCase
{
    use RefreshDatabase;

    protected array $seededBlogs = [];

    protected function setUp(): void
    {
        parent::setUp();

        $author = User::factory()->create([
            'is_admin' => true,
            'password' => 'password',
        ]);

        $category = BlogCategory::create();
        $category->translations()->create([
            'locale' => 'fa',
            'name' => 'مهاجرت و تحصیل',
            'slug' => 'immigration-study',
        ]);

        $fixturesJson = file_get_contents(base_path('tests/Feature/fixtures_persian_blogs.json'));
        $fixtures = json_decode($fixturesJson, true);

        foreach ($fixtures as $slug => $data) {
            $blog = Blog::create([
                'id' => $data['blog_post_id'],
                'category_id' => $category->id,
                'author_id' => $author->id,
                'published_at' => now()->subDays(10),
            ]);

            $translation = BlogPostTranslation::create([
                'blog_post_id' => $blog->id,
                'locale' => 'fa',
                'title' => $data['title'],
                'slug' => $data['slug'],
                'excerpt' => $data['excerpt'],
                'body' => $data['body'],
            ]);

            $this->seededBlogs[$slug] = [
                'blog' => $blog,
                'translation' => $translation,
            ];
        }
    }

    public function test_migration_normalizes_relative_links_and_enriches_persian_blogs(): void
    {
        // 1. Assert before migration that relative links exist in initial fixtures
        $initialCampusPost = BlogPostTranslation::where('locale', 'fa')
            ->where('slug', 'campus-france-2026-complete-guide-iranian-students')
            ->first();

        $this->assertStringContainsString('../../../fa/services/', $initialCampusPost->body);

        // 2. Run the migration up()
        $migration = require database_path('migrations/2026_09_13_150000_enrich_persian_blog_posts_with_cluster_links.php');
        $migration->up();

        // 3. Assert relative links are normalized across all 7 blogs
        foreach ($this->seededBlogs as $slug => $item) {
            $updated = BlogPostTranslation::where('locale', 'fa')
                ->where('slug', $slug)
                ->first();

            $this->assertNotNull($updated, "Failed to find updated blog translation for slug: {$slug}");

            // Verify no relative links remain
            $this->assertStringNotContainsString('../../fa/', $updated->body, "Found relative link in {$slug}");
            $this->assertStringNotContainsString('../../../fa/', $updated->body, "Found relative link in {$slug}");

            // Verify enrichment badge or marker exists
            $this->assertStringContainsString('GEO_CLUSTER_ENRICHMENT_2026', $updated->body, "Enrichment missing for {$slug}");
            $this->assertStringContainsString('/fa/consult', $updated->body, "Consultation link missing in {$slug}");
        }

        // 4. Assert specific cluster links for individual articles
        // Article 1: Campus France -> Lyon (1, 2, 3), Toulouse, Sciences Po, Paris Cité
        $campus = BlogPostTranslation::where('slug', 'campus-france-2026-complete-guide-iranian-students')->first();
        $this->assertStringContainsString('/fa/universities/lyon-1', $campus->body);
        $this->assertStringContainsString('/fa/universities/lyon-2', $campus->body);
        $this->assertStringContainsString('/fa/universities/lyon-3', $campus->body);
        $this->assertStringContainsString('/fa/universities/toulouse', $campus->body);
        $this->assertStringContainsString('/fa/universities/sciences-po', $campus->body);
        $this->assertStringContainsString('/fa/universities/paris-cite', $campus->body);
        $this->assertStringContainsString('/fa/cities/lyon', $campus->body);
        $this->assertStringContainsString('/fa/cities/toulouse', $campus->body);
        $this->assertStringContainsString('/fa/cities/paris', $campus->body);
        $this->assertStringContainsString('/fa/cities/strasbourg', $campus->body);

        // Article 2: Financial Proof -> Paris, Lyon, Toulouse, Strasbourg
        $financial = BlogPostTranslation::where('slug', 'france-student-visa-financial-proof-2026')->first();
        $this->assertStringContainsString('/fa/cities/paris', $financial->body);
        $this->assertStringContainsString('/fa/cities/lyon', $financial->body);
        $this->assertStringContainsString('/fa/cities/toulouse', $financial->body);
        $this->assertStringContainsString('/fa/cities/strasbourg', $financial->body);

        // Article 3: Best Fields -> Aerospace Toulouse, Medicine Paris Cité & Lyon 1, Sciences Po, Lyon 3 Law
        $fields = BlogPostTranslation::where('slug', 'بهترین-رشته-های-تحصیلی-در-فرانسه')->first();
        $this->assertStringContainsString('/fa/universities/toulouse', $fields->body);
        $this->assertStringContainsString('/fa/universities/paris-cite', $fields->body);
        $this->assertStringContainsString('/fa/universities/lyon-1', $fields->body);
        $this->assertStringContainsString('/fa/universities/sciences-po', $fields->body);

        // Article 4: Real Estate -> Lyon, Toulouse, Paris, Strasbourg
        $realEstate = BlogPostTranslation::where('slug', 'خرید-ملک-در-فرانسه-اتباع-ایرانی-۲۰۲۶')->first();
        $this->assertStringContainsString('/fa/cities/lyon', $realEstate->body);
        $this->assertStringContainsString('/fa/cities/toulouse', $realEstate->body);
        $this->assertStringContainsString('/fa/cities/paris', $realEstate->body);
        $this->assertStringContainsString('/fa/cities/strasbourg', $realEstate->body);

        // Article 7: Strasbourg -> Strasbourg Hub, Lyon, Toulouse, Paris
        $strasbourg = BlogPostTranslation::where('slug', 'تحصیل-در-استراسبورگ-فرانسه-۲۰۲۶')->first();
        $this->assertStringContainsString('/fa/cities/strasbourg', $strasbourg->body);
        $this->assertStringContainsString('/fa/cities/lyon', $strasbourg->body);
        $this->assertStringContainsString('/fa/cities/toulouse', $strasbourg->body);
        $this->assertStringContainsString('/fa/cities/paris', $strasbourg->body);
    }

    public function test_all_seven_persian_blog_endpoints_render_successfully_with_enriched_content(): void
    {
        // Run migration
        $migration = require database_path('migrations/2026_09_13_150000_enrich_persian_blog_posts_with_cluster_links.php');
        $migration->up();

        foreach (array_keys($this->seededBlogs) as $slug) {
            $url = '/fa/blog/'.rawurlencode($slug);
            $response = $this->get($url);

            $response->assertStatus(200);
            $response->assertSee('/fa/consult', false);
            $response->assertSee('GEO_CLUSTER_ENRICHMENT_2026', false);
            $response->assertDontSee('../../fa/', false);
        }
    }

    public function test_migration_down_removes_enriched_content_cleanly(): void
    {
        $migration = require database_path('migrations/2026_09_13_150000_enrich_persian_blog_posts_with_cluster_links.php');
        $migration->up();

        // Down
        $migration->down();

        foreach (array_keys($this->seededBlogs) as $slug) {
            $record = BlogPostTranslation::where('locale', 'fa')->where('slug', $slug)->first();
            $this->assertStringNotContainsString('GEO_CLUSTER_ENRICHMENT_2026', $record->body);
        }
    }
}
