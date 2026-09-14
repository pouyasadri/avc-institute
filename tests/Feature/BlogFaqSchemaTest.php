<?php

namespace Tests\Feature;

use App\Helpers\FaqExtractor;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\BlogPostTranslation;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Blade;
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

    public function test_faq_extractor_splits_content_and_faqs_correctly(): void
    {
        $html = <<<'HTML'
        <p>This is the narrative content of the blog post.</p>
        <div class="my-5 p-5 text-center text-white bg-primary">CTA Banner</div>
        <h3 class="h3 mt-5 mb-4 fw-bold text-center">Frequently Asked Questions</h3>
        <div id="faq-accordion" class="faq-accordion">
            <div class="faq-item mb-4">
                <span class="faq-question fw-bold"> How much is the tuition fee? </span>
                <div class="faq-answer text-muted">
                    <p>Tuition fees in French public universities are heavily subsidized.</p>
                </div>
            </div>
        </div>
        HTML;

        $result = FaqExtractor::splitContentAndFaqs($html);

        $this->assertStringContainsString('This is the narrative content of the blog post.', $result['content']);
        $this->assertStringContainsString('CTA Banner', $result['content']);
        $this->assertStringNotContainsString('faq-accordion', $result['content']);
        $this->assertStringNotContainsString('Frequently Asked Questions', $result['content']);

        $this->assertEquals('Frequently Asked Questions', $result['title']);
        $this->assertCount(1, $result['faqs']);
        $this->assertEquals('How much is the tuition fee?', $result['faqs'][0]['question']);
        $this->assertEquals('Tuition fees in French public universities are heavily subsidized.', $result['faqs'][0]['answer']);
    }

    public function test_faq_extractor_returns_empty_when_no_faq_markup(): void
    {
        $this->assertEmpty(FaqExtractor::extractFromHtml(null));
        $this->assertEmpty(FaqExtractor::extractFromHtml(''));
        $this->assertEmpty(FaqExtractor::extractFromHtml('<p>Just a normal blog paragraph.</p>'));

        $split = FaqExtractor::splitContentAndFaqs('<p>Simple post.</p>');
        $this->assertEquals('<p>Simple post.</p>', $split['content']);
        $this->assertEmpty($split['faqs']);
        $this->assertNull($split['title']);
    }

    public function test_centralized_faq_component_renders_inline_and_full_section_modes(): void
    {
        $items = [
            ['question' => 'What is Campus France?', 'answer' => '<p>The official French agency.</p>'],
        ];

        // 1. Full section mode (default, used on city and university pages)
        $fullRender = Blade::render(
            '<x-sections.faq :items="$items" title="General FAQ" />',
            ['items' => $items]
        );

        $this->assertStringContainsString('<section class="faq-area pt-100 pb-70">', $fullRender);
        $this->assertStringContainsString('General FAQ', $fullRender);
        $this->assertStringContainsString('What is Campus France?', $fullRender);
        $this->assertStringContainsString('bx-chevron-down', $fullRender);

        // 2. Inline mode (used inside blog post articles)
        $inlineRender = Blade::render(
            '<x-sections.faq :items="$items" title="Blog FAQ" id="blog-faq-accordion" :inline="true" />',
            ['items' => $items]
        );

        $this->assertStringNotContainsString('<section class="faq-area', $inlineRender);
        $this->assertStringContainsString('<div class="faq-container-inline my-5">', $inlineRender);
        $this->assertStringContainsString('id="blog-faq-accordion"', $inlineRender);
        $this->assertStringContainsString('Blog FAQ', $inlineRender);
        $this->assertStringContainsString('What is Campus France?', $inlineRender);
        $this->assertStringContainsString('bx-chevron-down', $inlineRender);
    }

    public function test_blog_show_renders_centralized_faq_component_and_schema(): void
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
        <h3 class="h3 mt-5 mb-4 fw-bold text-center">پاسخ به سوالات متداول تمکن مالی</h3>
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

        // Assert centralized component rendered in inline mode
        $response->assertSee('id="blog-faq-accordion"', false);
        $response->assertSee('bx-chevron-down', false);
        $response->assertSee('bx-help-circle', false);

        // Assert content and FAQs rendered
        $response->assertSee('مقدمه مقاله', false);
        $response->assertSee('حداقل تمکن مالی فرانسه ۲۰۲۶ چقدر است؟', false);
        $response->assertSee('حداقل ۶۱۵ یورو در ماه طبق نرخ حواله سنا.', false);

        // Assert Schema.org FAQPage emitted
        $response->assertSee('"@type": "FAQPage"', false);
        $response->assertSee('"@type": "Question"', false);
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
        $response->assertDontSee('id="blog-faq-accordion"', false);
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
