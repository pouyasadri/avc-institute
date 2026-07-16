@extends('layouts.main')

@php
    $currentLocale = app()->getLocale();
    $seoService = app(\App\Services\SeoService::class);
    $seoService->forBlogPost($blog, $currentLocale);

    $pageTitle = $translation->title;
    $pageDescription = $translation->excerpt ?? \Illuminate\Support\Str::limit(strip_tags($translation->body), 160, '...');
    $pageKeywords = $translation->title;

    $imagePath = $blog->main_image;

    // SEO: Reading Time Estimation
    $wordCount = str_word_count(strip_tags($translation->body));
    $readingTime = max(1, ceil($wordCount / 200)); // Average 200 words per minute
@endphp

@section('title', $pageTitle)
@section("keywords", $pageKeywords)
@section("description", $pageDescription)

@section('content')
    <!-- Start Page Title Area -->
    <header class="page-title-area" role="banner">
        <div class="container">
            <div class="page-title-content">
                <x-premium-breadcrumb :items="[
            ['url' => route('index', ['locale' => $currentLocale]), 'label' => __('blog/show.breadcrumb_home')],
            ['url' => route('blog.index', ['locale' => $currentLocale]), 'label' => __('blog/show.breadcrumb_blogs')]
        ]" />
                <h1 class="h2">{{$translation->title}}</h1>
            </div>
        </div>
    </header>

    <!-- Start Blog Details Area -->
    <section class="blog-details-area ptb-100">
        <div class="container">
            <div class="row">
                <div class="col-lg-8">
                    <article class="blog-details-content" id="mydiv">
                        <header>
                            @if($blog->main_image)
                                <div class="blog-details-img mb-4">
                                    <img src="{{ \Storage::disk('s3')->url($imagePath) }}" alt="{{ $translation->title }}"
                                        class="rounded-4 shadow-sm w-100" style="max-height: 500px; object-fit: cover;">
                                </div>
                            @endif

                            <div class="blog-details-meta mb-4">
                                <ul class="d-flex flex-wrap gap-3 list-unstyled">
                                    <li title="Published Date">
                                        <i class="bx bx-calendar text-primary"></i>
                                        <time
                                            datetime="{{ ($blog->published_at ?? $blog->created_at)->toIso8601String() }}">
                                            {{ ($blog->published_at ?? $blog->created_at)->format('M d, Y') }}
                                        </time>
                                    </li>
                                    @if($blog->category)
                                        <li title="Category">
                                            <i class="bx bx-folder text-primary"></i>
                                            <a href="{{ route('blog.categories.index', ['locale' => $currentLocale]) }}"
                                               class="text-decoration-none text-muted">
                                                {{ $blog->category->translations->where('locale', $currentLocale)->first()->name ?? $blog->category->name }}
                                            </a>
                                        </li>
                                    @endif
                                    <li title="Reading Time">
                                        <i class="bx bx-time text-primary"></i>
                                        {{ __('blog/show.reading_time', ['minutes' => $readingTime]) }}
                                    </li>
                                </ul>
                            </div>
                        </header>

                        <div class="article-content"
                            style="font-family: 'Inter', sans-serif; line-height: 1.8; color: #4a5568;">
                            {!! $translation->body !!}
                        </div>

                        <footer class="post-navigation mt-5 pt-4 border-top">
                            <div class="row align-items-center">
                                <nav class="col-6 text-start" aria-label="Previous post">
                                    @if ($prevBlog)
                                        <a href="{{ route('blog.show', ['locale' => $currentLocale, 'blog' => $prevBlog->id]) }}"
                                            class="d-flex align-items-center gap-2 group text-decoration-none">
                                            <div class="nav-btn-circle shadow-sm">
                                                <i class="{{ $isRtl ? 'bx bx-right-arrow-alt' : 'bx bx-left-arrow-alt' }}"></i>
                                            </div>
                                            <div class="d-none d-md-block">
                                                <span
                                                    class="d-block text-muted small">{{ __('blog/show.previous_blog') }}</span>
                                                <span
                                                    class="fw-bold text-dark">{{ \Illuminate\Support\Str::limit($prevBlog->getTranslation($currentLocale)?->title, 30) }}</span>
                                            </div>
                                        </a>
                                    @endif
                                </nav>
                                <nav class="col-6 text-end" aria-label="Next post">
                                    @if ($nextBlog)
                                        <a href="{{ route('blog.show', ['locale' => $currentLocale, 'blog' => $nextBlog->id]) }}"
                                            class="d-flex align-items-center justify-content-end gap-2 group text-decoration-none">
                                            <div class="d-none d-md-block">
                                                <span class="d-block text-muted small">{{ __('blog/show.next_blog') }}</span>
                                                <span
                                                    class="fw-bold text-dark">{{ \Illuminate\Support\Str::limit($nextBlog->getTranslation($currentLocale)?->title, 30) }}</span>
                                            </div>
                                            <div class="nav-btn-circle shadow-sm">
                                                <i class="{{ $isRtl ? 'bx bx-left-arrow-alt' : 'bx bx-right-arrow-alt' }}"></i>
                                            </div>
                                        </a>
                                    @endif
                                </nav>
                            </div>
                        </footer>

                        {{-- Comments Section --}}
                        <div class="comments-area mt-5">
                            @php
                                $comments = $blog->comments()
                                    ->where('locale', $currentLocale)
                                    ->where('is_approved', true) // Show only approved comments
                                    ->orderBy('created_at', 'desc')
                                    ->get();
                            @endphp

                            @if($comments->count() > 0)
                                <h3 class="comments-title mb-4">{{ $comments->count() }} {{ __('blog/show.comments_title') }}
                                </h3>
                                <ul class="list-unstyled">
                                    @foreach($comments as $comment)
                                        <li class="comment-item mb-4 p-4 rounded-4 bg-light shadow-sm">
                                            <div class="d-flex gap-3">
                                                <div class="flex-shrink-0">
                                                    <div class="avatar rounded-circle bg-primary text-white d-flex align-items-center justify-content-center"
                                                        style="width: 50px; height: 50px; font-size: 1.25rem;">
                                                        {{ strtoupper(substr($comment->name, 0, 1)) }}
                                                    </div>
                                                </div>
                                                <div class="flex-grow-1">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <h5 class="mb-0">{{ $comment->name }}</h5>
                                                        <span
                                                            class="text-muted small">{{ $comment->created_at->format('M d, Y') }}</span>
                                                    </div>
                                                    <p class="mb-0 text-secondary">{{ $comment->body }}</p>
                                                </div>
                                            </div>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
                        </div>

                        <div class="ask-question mt-5 p-4 p-md-5 rounded-4 shadow-sm bg-light">
                            <h3 class="mb-4">{{ __('blog/show.ask_question_title') }}</h3>

                            @if(session('success'))
                                <div class="alert alert-success alert-dismissible fade show" role="alert">
                                    {{ session('success') }}
                                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                                </div>
                            @endif

                            <form id="contactForm"
                                action="{{ route('comments.store', ['locale' => $currentLocale, 'blog' => $blog->id]) }}"
                                method="POST">
                                @csrf
                                <div class="row">
                                    <div class="col-lg-6 col-sm-6 mb-3">
                                        <label for="name" class="visually-hidden">Name</label>
                                        <input type="text" name="name" id="name" class="form-control py-3 rounded-3"
                                            required placeholder="{{ __('blog/show.name_placeholder') }}">
                                    </div>
                                    <div class="col-lg-6 col-sm-6 mb-3">
                                        <label for="email" class="visually-hidden">Email</label>
                                        <input type="email" name="email" id="email" class="form-control py-3 rounded-3"
                                            required placeholder="{{ __('blog/show.email_placeholder') }}">
                                    </div>
                                    <div class="col-lg-12 mb-3">
                                        <label for="msg_subject" class="visually-hidden">Subject</label>
                                        <input type="text" name="msg_subject" id="msg_subject"
                                            class="form-control py-3 rounded-3"
                                            placeholder="{{ __('blog/show.subject_placeholder') }}">
                                    </div>
                                    <div class="col-lg-12 mb-4">
                                        <label for="message" class="visually-hidden">Message</label>
                                        <textarea name="message" class="form-control rounded-3" id="message" rows="5"
                                            required placeholder="{{ __('blog/show.message_placeholder') }}"></textarea>
                                    </div>
                                    <div class="col-lg-12">
                                        <button type="submit" class="default-btn border-0 w-100">
                                            {{ __('blog/show.submit_button') }}
                                            <i class="{{ $flaticonArrow }}"></i>
                                        </button>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </article>
                </div>

                <div class="col-lg-4 mt-5 mt-lg-0">
                    <aside class="blog-sidebar">
                        <!-- Table of Contents -->
                        <nav class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4" aria-label="Table of contents">
                            <h4 class="widget-title mb-3 fs-5 fw-bold border-bottom pb-2">
                                {{ __('blog/show.table_of_contents') }}
                            </h4>
                            <ol id="board" class="ps-3 mb-0" style="font-size: 0.95rem;"></ol>
                        </nav>

                        <!-- Recent Posts -->
                        @if($recentBlogs->count() > 0)
                            <section class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4" aria-label="Recent posts">
                                <h4 class="widget-title mb-4 fs-5 fw-bold border-bottom pb-2">
                                    {{ __('blog/show.recent_posts_title') }}
                                </h4>
                                <div class="recent-posts">
                                    @foreach($recentBlogs as $recent)
                                        @php
                                            $recentTranslation = $recent->getTranslation($currentLocale);
                                            $recentImage = $recent->main_image;
                                        @endphp
                                        <article class="recent-post-item d-flex gap-3 mb-3 pb-3 border-bottom">
                                            <div class="recent-post-img flex-shrink-0">
                                                <a
                                                    href="{{ route('blog.show', ['locale' => $currentLocale, 'blog' => $recent->id]) }}">
                                                    @if($recent->main_image)
                                                        <img src="{{ \Storage::disk('s3')->url($recentImage) }}"
                                                            alt="{{ $recentTranslation->title }}" class="rounded-4 shadow-sm"
                                                            style="width: 70px; height: 70px; object-fit: cover;">
                                                    @else
                                                        <div class="bg-light rounded-4 d-flex align-items-center justify-content-center"
                                                            style="width: 70px; height: 70px;">
                                                            <i class="bx bx-image text-muted"></i>
                                                        </div>
                                                    @endif
                                                </a>
                                            </div>
                                            <div class="recent-post-content">
                                                <h5 class="fs-6 mb-1">
                                                    <a href="{{ route('blog.show', ['locale' => $currentLocale, 'blog' => $recent->id]) }}"
                                                        class="text-dark text-decoration-none fw-bold">
                                                        {{ \Illuminate\Support\Str::limit($recentTranslation->title, 50) }}
                                                    </a>
                                                </h5>
                                                <span class="text-muted small">
                                                    <i class="bx bx-calendar me-1"></i>
                                                    <time
                                                        datetime="{{ ($recent->published_at ?? $recent->created_at)->toIso8601String() }}">
                                                        {{ ($recent->published_at ?? $recent->created_at)->format('M d, Y') }}
                                                    </time>
                                                </span>
                                            </div>
                                        </article>
                                    @endforeach
                                </div>
                            </section>
                        @endif

                        <!-- Contact Sidebar -->
                        <div class="sidebar-widget p-4 rounded-5 shadow-sm bg-white text-center"
                            style="background: linear-gradient(135deg, #0F3A80 0%, #24416b 100%);">
                            <h4 class="text-white mb-4 fs-5 fw-bold">{{ __('blog/show.contact_us') }}</h4>
                            <div class="d-grid gap-3">
                                <a href="{{ route('consult', ['locale' => $currentLocale]) }}"
                                    class="btn btn-warning rounded-pill py-3 fw-bold shadow-sm">
                                    {{ __('blog/show.consult_button') }}
                                </a>
                                <nav class="d-flex justify-content-center gap-3 mt-2" aria-label="Social contact">
                                    <a href="mailto:info@applyvipconseil.com" class="text-white fs-4 hover-lift"
                                        aria-label="Email us">
                                        <i class='bx bx-envelope'></i>
                                    </a>
                                    <a href="https://www.instagram.com/apply_vip_conseil/" target="_blank"
                                        rel="noopener noreferrer"
                                        class="text-white fs-4 hover-lift" aria-label="Follow us on Instagram">
                                        <i class='bx bxl-instagram'></i>
                                    </a>
                                </nav>
                            </div>
                        </div>
                    </aside>
                </div>
            </div>
        </div>
    </section>

    <script src="{{asset("assets/js/createScrollLinks.js")}}"></script>
@endsection

@push("json")
    @php
        $blogSchema = new \App\Services\StructuredData\BlogPostingSchema($blog, $currentLocale);
    @endphp
    <x-seo.structured-data :schema="$blogSchema" />

    @php
        $breadcrumbSchema = \App\Services\StructuredData\BreadcrumbSchema::fromArray([
            ['name' => __('blog/show.breadcrumb_home'), 'url' => route('index', ['locale' => $currentLocale])],
            ['name' => __('blog/show.breadcrumb_blogs'), 'url' => route('blog.index', ['locale' => $currentLocale])],
            ['name' => $translation->title, 'url' => route('blog.show', ['locale' => $currentLocale, 'blog' => $blog->id])],
        ]);
    @endphp
    <x-seo.structured-data :schema="$breadcrumbSchema" />
@endpush