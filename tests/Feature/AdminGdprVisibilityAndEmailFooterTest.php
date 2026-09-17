<?php

namespace Tests\Feature;

use App\Mail\ConsultationConfirmation;
use App\Mail\ContactFormConfirmation;
use App\Mail\QuestionConfirmation;
use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Comment;
use App\Models\ConsultingSubmission;
use App\Models\ContactSubmission;
use App\Models\QuestionSubmission;
use App\Models\User;
use App\Services\LocaleDetector;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Http\Request;
use Tests\TestCase;

class AdminGdprVisibilityAndEmailFooterTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_sidebar_renders_gdpr_data_rights_link()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'password' => 'password',
        ]);

        $response = $this->actingAs($admin)->get('/admin');
        $response->assertStatus(200);
        $response->assertSee(route('admin.data-rights.index'));
        $response->assertSee('RGPD');
        $response->assertSee('Droits');
    }

    public function test_admin_consulting_index_and_show_render_consent_badges()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'password' => 'password',
        ]);

        $consented = ConsultingSubmission::create([
            'name' => 'Alice Martin',
            'email' => 'alice@example.com',
            'service' => 'Student Visa',
            'details' => 'Need advice on admission',
            'locale' => 'fr',
            'gdpr_consent' => true,
            'consent_given_at' => now(),
            'privacy_policy_version' => '1.0',
        ]);

        $legacy = ConsultingSubmission::create([
            'name' => 'Bob Smith',
            'email' => 'bob@example.com',
            'service' => 'Housing',
            'details' => 'Need apartment',
            'locale' => 'en',
            'gdpr_consent' => false,
        ]);

        $responseIndex = $this->actingAs($admin)->get(route('admin.consulting.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('v1.0');
        $responseIndex->assertSee('Ancien');

        $responseShow = $this->actingAs($admin)->get(route('admin.consulting.show', $consented->id));
        $responseShow->assertStatus(200);
        $responseShow->assertSee('Accordé (v1.0)');
    }

    public function test_admin_questions_index_and_show_render_consent_badges()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'password' => 'password',
        ]);

        $question = QuestionSubmission::create([
            'name' => 'Sara Rezaei',
            'email' => 'sara@example.com',
            'phone_number' => '+33712345678',
            'subject' => 'University inquiry',
            'message' => 'Tuition question',
            'page_type' => 'university',
            'page_name' => 'Paris Saclay',
            'locale' => 'fa',
            'gdpr_consent' => true,
            'consent_given_at' => now(),
            'privacy_policy_version' => '1.0',
        ]);

        $responseIndex = $this->actingAs($admin)->get(route('admin.questions.index'));
        $responseIndex->assertStatus(200);
        $responseIndex->assertSee('v1.0');

        $responseShow = $this->actingAs($admin)->get(route('admin.questions.show', $question->id));
        $responseShow->assertStatus(200);
        $responseShow->assertSee('Accordé (v1.0)');
    }

    public function test_admin_comments_index_renders_consent_badges()
    {
        $admin = User::factory()->create([
            'is_admin' => true,
            'password' => 'password',
        ]);

        $category = BlogCategory::create();
        $blog = Blog::create([
            'category_id' => $category->id,
            'is_pinned' => false,
            'published_at' => now(),
        ]);

        Comment::create([
            'blog_post_id' => $blog->id,
            'name' => 'Jean Dupont',
            'email' => 'jean@example.com',
            'body' => 'Great article on visa requirements!',
            'locale' => 'fr',
            'is_approved' => false,
            'gdpr_consent' => true,
            'consent_given_at' => now(),
            'privacy_policy_version' => '1.0',
        ]);

        $response = $this->actingAs($admin)->get(route('admin.comments.index'));
        $response->assertStatus(200);
        $response->assertSee('v1.0');
    }

    public function test_confirmation_emails_contain_gdpr_disclosure_panel_and_links()
    {
        $contact = new ContactSubmission([
            'name' => 'Kaveh',
            'email' => 'kaveh@example.com',
            'subject' => 'Inquiry',
            'message' => 'Hello',
            'locale' => 'fa',
        ]);

        $contactMail = new ContactFormConfirmation($contact);
        $contactHtml = $contactMail->render();
        $this->assertStringContainsString('privacy', $contactHtml);
        $this->assertStringContainsString('data-rights', $contactHtml);

        $consultData = [
            'user_name' => 'Claire',
            'user_email' => 'claire@example.com',
            'user_service' => 'Master',
            'user_phone_number' => '+33123456789',
            'user_details' => 'Consultation info',
            'locale' => 'fr',
        ];

        $consultMail = new ConsultationConfirmation($consultData);
        $consultHtml = $consultMail->render();
        $this->assertStringContainsString('privacy', $consultHtml);
        $this->assertStringContainsString('data-rights', $consultHtml);

        $question = new QuestionSubmission([
            'name' => 'David',
            'email' => 'david@example.com',
            'page_name' => 'Sorbonne',
            'subject' => 'Admission',
            'message' => 'When is deadline?',
            'locale' => 'en',
        ]);

        $questionMail = new QuestionConfirmation($question);
        $questionHtml = $questionMail->render();
        $this->assertStringContainsString('privacy', $questionHtml);
        $this->assertStringContainsString('data-rights', $questionHtml);
    }

    public function test_locale_detector_defaults_to_persian_when_no_browser_or_session()
    {
        config(['localization.enable_ip_detection' => false]);

        $request = Request::create('/', 'GET', [], [], [], ['HTTP_ACCEPT_LANGUAGE' => '']);
        $detector = new LocaleDetector;
        $locale = $detector->detectLocale($request);

        $this->assertEquals('fa', $locale);
    }
}
