<?php

namespace Tests\Feature;

use App\Models\Blog;
use App\Models\BlogCategory;
use App\Models\Comment;
use App\Models\ContactSubmission;
use App\Models\DataRightsRequest;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Log;
use Tests\TestCase;

class AdminDataRightsExportAndCommentErasureTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->create([
            'is_admin' => true,
            'password' => 'password',
        ]);
    }

    public function test_admin_can_export_personal_data_as_json(): void
    {
        $email = 'exporter@example.com';

        ContactSubmission::create([
            'name' => 'Exporter User',
            'email' => $email,
            'phone_number' => '+33123456789',
            'subject' => 'Hello Export',
            'message' => 'Please export my data.',
            'locale' => 'fr',
            'gdpr_consent' => true,
        ]);

        $category = BlogCategory::create(['name' => 'News', 'slug' => 'news']);
        $blog = Blog::create([
            'blog_category_id' => $category->id,
            'slug' => 'test-blog-export',
            'status' => 'published',
        ]);

        Comment::create([
            'blog_post_id' => $blog->id,
            'locale' => 'fr',
            'name' => 'Exporter User',
            'email' => $email,
            'subject' => 'Great post',
            'body' => 'I love this article!',
            'is_approved' => true,
            'gdpr_consent' => true,
        ]);

        $request = DataRightsRequest::create([
            'email' => $email,
            'request_type' => 'portability',
            'status' => 'pending',
            'token' => 'dummy-token',
            'locale' => 'fr',
        ]);

        $response = $this->actingAs($this->admin)
            ->get(route('admin.data-rights.export', $request->id));

        $response->assertOk();
        $response->assertHeader('Content-Type', 'application/json');
        $this->assertStringContainsString('avc-data-export-'.$request->id.'.json', $response->headers->get('Content-Disposition') ?? '');

        $data = json_decode($response->getContent(), true);
        $this->assertEquals($email, $data['requester']['email']);
        $this->assertCount(1, $data['contact_submissions']);
        $this->assertCount(1, $data['comments']);
        $this->assertEquals('Great post', $data['comments'][0]['subject']);
    }

    public function test_admin_erasure_soft_deletes_comments_and_hashes_email_in_log(): void
    {
        $email = 'erasure.target@example.com';

        ContactSubmission::create([
            'name' => 'Target User',
            'email' => $email,
            'phone_number' => '+33987654321',
            'subject' => 'Erase Me',
            'message' => 'Please erase all my data.',
            'locale' => 'fr',
            'gdpr_consent' => true,
        ]);

        $category = BlogCategory::create(['name' => 'Updates', 'slug' => 'updates']);
        $blog = Blog::create([
            'blog_category_id' => $category->id,
            'slug' => 'test-blog-erasure',
            'status' => 'published',
        ]);

        $comment = Comment::create([
            'blog_post_id' => $blog->id,
            'locale' => 'fr',
            'name' => 'Target User',
            'email' => $email,
            'subject' => 'Comment to erase',
            'body' => 'My comment body',
            'is_approved' => true,
            'gdpr_consent' => true,
        ]);

        $request = DataRightsRequest::create([
            'email' => $email,
            'request_type' => 'erasure',
            'status' => 'pending',
            'token' => 'token-to-erase',
            'locale' => 'fr',
        ]);

        $expectedHash = hash('sha256', $email);

        Log::shouldReceive('info')
            ->once()
            ->withArgs(function ($message) use ($request, $expectedHash, $email) {
                // Must contain request ID and hash, but MUST NOT contain raw email!
                return str_contains($message, (string) $request->id)
                    && str_contains($message, $expectedHash)
                    && ! str_contains($message, $email);
            });

        $response = $this->actingAs($this->admin)
            ->delete(route('admin.data-rights.erase', $request->id));

        $response->assertRedirect(route('admin.data-rights.index'));

        // Contact submission and Comment must be soft-deleted
        $this->assertSoftDeleted('contact_submissions', ['email' => $email]);
        $this->assertSoftDeleted('comments', ['id' => $comment->id]);
    }

    public function test_gdpr_purge_anonymises_data_rights_request_ip_and_force_deletes_old_comments(): void
    {
        // 1. DataRightsRequest older than 90 days with IP
        $oldRequest = DataRightsRequest::create([
            'email' => 'old.requester@example.com',
            'request_type' => 'access',
            'status' => 'completed',
            'token' => 'old-token',
            'locale' => 'fr',
            'ip_address' => '192.168.1.100',
        ]);
        $oldRequest->created_at = Carbon::now()->subDays(95);
        $oldRequest->save();

        // 2. Soft-deleted comment past 30 days grace period
        $category = BlogCategory::create(['name' => 'General', 'slug' => 'general']);
        $blog = Blog::create([
            'blog_category_id' => $category->id,
            'slug' => 'test-blog-purge',
            'status' => 'published',
        ]);

        $oldComment = Comment::create([
            'blog_post_id' => $blog->id,
            'locale' => 'fr',
            'name' => 'Old Commenter',
            'email' => 'old.commenter@example.com',
            'subject' => 'Old Comment',
            'body' => 'Body to purge',
            'is_approved' => true,
            'gdpr_consent' => true,
        ]);
        $oldComment->delete();
        $oldComment->deleted_at = Carbon::now()->subDays(35);
        $oldComment->save();

        Artisan::call('gdpr:purge');

        // IP should now be null on the old request
        $this->assertNull($oldRequest->fresh()->ip_address);

        // Soft-deleted comment should be permanently deleted
        $this->assertDatabaseMissing('comments', ['id' => $oldComment->id]);
    }
}
