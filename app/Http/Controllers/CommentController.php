<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreCommentRequest;
use App\Models\Comment;
use App\Services\Blog\BlogSlugResolver;
use Illuminate\Http\RedirectResponse;

class CommentController extends Controller
{
    public function __construct(protected BlogSlugResolver $slugResolver) {}

    public function store(StoreCommentRequest $request, string $locale, string $blog): RedirectResponse
    {
        $translation = $this->slugResolver->resolve($locale, $blog);

        if (! $translation) {
            return redirect()
                ->route('blog.index', ['locale' => $locale])
                ->with('error', __('messages.blog_not_found'));
        }

        $validated = $request->validated();

        Comment::create([
            'blog_post_id' => $translation->blog_post_id,
            'locale' => $locale,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['msg_subject'] ?? null,
            'body' => strip_tags($validated['message']),
            'is_approved' => false,
            'gdpr_consent' => true,
            'consent_given_at' => now(),
            'privacy_policy_version' => config('gdpr.privacy_policy_version', '1.0'),
        ]);

        return redirect()
            ->back()
            ->with('success', __('blog/show.comment_submitted'));
    }
}
