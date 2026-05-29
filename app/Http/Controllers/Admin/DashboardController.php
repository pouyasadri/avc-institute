<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Blog;
use App\Models\Comment;
use App\Models\ConsultingSubmission;
use App\Models\QuestionSubmission;

class DashboardController extends Controller
{
    public function index()
    {
        $stats = [
            'blogs' => Blog::count(),
            'questions' => QuestionSubmission::count(),
            'comments' => Comment::count(),
            'consultations' => ConsultingSubmission::count(),
            'pending_comments' => Comment::where('is_approved', false)->count(),
        ];

        // Fetch recent activity (simple implementation: latest 5 from each, merged and sorted could be complex,
        // starting with just latest 5 blogs/questions for now or just keeping it simple)
        $recentBlogs = Blog::latest()->take(5)->get();

        return view('admin.dashboard', compact('stats', 'recentBlogs'));
    }
}
