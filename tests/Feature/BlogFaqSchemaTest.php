<?php

namespace Tests\Feature;

use App\Helpers\FaqExtractor;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogPostTranslation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class BlogFaqSchemaTest extends TestCase
{
    use RefreshDatabase;

    public function test_faq_extractor_correctly_parses_qa_pairs(): void
    {
        $html = <<<'HTML'
        <div id="faq-accordion" class="faq-accordion">
            <div class="faq-item mb-4">
                <span class="faq-question fw-bold"> Is the Campus France interview online? </span>
                <div class="faq-answer text-muted">
                    <p>Usually, the interview is conducted in-person at Campus France Tehran.</p>
                </div>
            </div>
            <div class="faq-item mb-4">
                <span class="faq-question fw-bold"> آیا مدارک به زبان فرانسوی نیاز است؟ </span>
                <div class="faq-answer text-muted">
                    <p>بله، ترجمه رسمی تمامی مدارک الزامی است.</p>
                </div>
            </div>
        </div>
        HTML;

        $faqs = FaqExtractor::extractFromHtml($html);

        $this->assertCount(2, $faqs);
        $this->assertEquals('Is the Campus France interview online?', $faqs[0]['question']);
        $this->assertEquals('Usually, the interview is conducted in-person at Campus France Tehran.', $faqs[0]['answer']);
        $this->assertEquals('آیا مدارک به زبان فرانسوی نیاز است؟', $faqs[1]['question']);
        $this->assertEquals('بله، ترجمه رسمی تمامی مدارک الزامی است.', $faqs[1]['answer']);
    }

    public function test_faq_extractor_returns_empty_when_no_faq_markup(): void
    {
        $this->assertEmpty(FaqExtractor::extractFromHtml(null));
        $this->assertEmpty(FaqExtractor::extractFromHtml(''));
        $this->assertEmpty(FaqExtractor::extractFromHtml('<p>Just a normal blog paragraph.</p>'));
    }

    public function test_blog_show_renders_faq_page_schema_when_faqs_exist(): void
    {
        $author = User::factory()->create([
            'password' => 'password',
        ]);

        $category = BlogCategory::create();
        $category->translations()->create([
            'locale' => 'fa',
            'name' => 'آموزش',
            'slug' => 'education',
        ]);

        $blog = Blog::create([
            'category_id' => $category->id,
            'author_id' => $author->id,
            'published_at' => now()->subDay(),
        ]);

        $bodyWithFaq = <<<'HTML'
        <p>مقدمه مقاله</p>
        <div id="faq-accordion" class="faq-accordion">
            <div class="faq-item mb-4">
                <span class="faq-question fw-bold"> حداقل تمکن مالی فرانسه ۲۰۲۶ چقدر است؟ </span>
                <div class="faq-answer text-muted">
                    <p>حداقل ۶۱۵ یورو در ماه طبق نرخ حواله سنا.</p>
                </div>
            </div>
        </div>
        HTML;

        BlogPostTranslation::create([
            'blog_post_id' => $blog->id,
            'locale' => 'fa',
            'title' => 'راهنمای تمکن مالی فرانسه',
            'slug' => 'france-financial-proof-guide',
            'body' => $bodyWithFaq,
        ]);

        $response = $this->get('/fa/blog/france-financial-proof-guide');

        $response->assertStatus(200);
        $response->assertSee('"@type": "FAQPage"', false);
        $response->assertSee('"@type": "Question"', false);
        $response->assertSee('حداقل تمکن مالی فرانسه ۲۰۲۶ چقدر است؟', false);
        $response->assertSee('حداقل ۶۱۵ یورو در ماه طبق نرخ حواله سنا.', false);
    }

    public function test_blog_show_omits_faq_schema_when_no_faqs_exist(): void
    {
        $author = User::factory()->create([
            'password' => 'password',
        ]);

        $category = BlogCategory::create();
        $category->translations()->create([
            'locale' => 'en',
            'name' => 'General',
            'slug' => 'general',
        ]);

        $blog = Blog::create([
            'category_id' => $category->id,
            'author_id' => $author->id,
            'published_at' => now()->subDay(),
        ]);

        BlogPostTranslation::create([
            'blog_post_id' => $blog->id,
            'locale' => 'en',
            'title' => 'Article Without FAQ',
            'slug' => 'article-without-faq',
            'body' => '<p>This is a standard article body with no FAQs.</p>',
        ]);

        $response = $this->get('/en/blog/article-without-faq');

        $response->assertStatus(200);
        $response->assertDontSee('"@type": "FAQPage"', false);
    }

    public function test_htaccess_does_not_contain_410_for_dirty_parameters(): void
    {
        $htaccess = file_get_contents(public_path('.htaccess'));

        $this->assertStringNotContainsString('R=410', $htaccess, '.htaccess should not contain R=410 for dirty parameters');
        $this->assertStringContainsString('(view|status)=', $htaccess);
        $this->assertStringContainsString('R=301', $htaccess);
    }

    public function test_sanitize_middleware_redirects_dirty_sciences_po_url(): void
    {
        $response = $this->get('/en/universities/sciences-po?view=university.sciences-po&status=200');

        $response->assertStatus(301);
        $response->assertRedirect('/en/universities/sciences-po');
    }
}
