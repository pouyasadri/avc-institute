<?php

namespace App\Http\Controllers;

use App\Models\Blog;
use App\Models\Comment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;

class CommentController extends Controller
{
    public function store(Request $request, string $locale, Blog $blog): RedirectResponse
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255',
            'msg_subject' => 'nullable|string|max:255',
            'message' => 'required|string',
            'gdpr_consent' => 'required|accepted',
        ]);

        Comment::create([
            'blog_post_id' => $blog->id,
            'locale' => $locale,
            'name' => $validated['name'],
            'email' => $validated['email'],
            'subject' => $validated['msg_subject'] ?? null,
            'body' => $validated['message'],
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
