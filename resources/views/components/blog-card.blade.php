@props(['blog'])

@php
    $currentLocale = app()->getLocale();
    $translation = $blog->translations->firstWhere('locale', $currentLocale) ?? $blog->translations->first();
    $categoryTranslation = $blog->category?->translations->firstWhere('locale', $currentLocale) ?? $blog->category?->translations->first();
@endphp

<div class="single-news h-100 d-flex flex-column position-relative">
    <div class="news-img">
        <a href="{{ route('blog.show', ['locale' => app()->getLocale(), 'blog' => $translation->slug ?? $blog->id]) }}">
            @if($blog->main_image)
                <img src="{{ $blog->main_image_url }}" style="aspect-ratio: 1.5/1; object-fit: cover;"
                    alt="{{ $translation->title ?? 'Blog post' }}" loading="lazy">
            @else
                <div class="bg-secondary text-white d-flex align-items-center justify-content-center"
                    style="aspect-ratio: 1.5/1;">
                    <i class="bx bx-image" style="font-size: 3rem;"></i>
                </div>
            @endif
        </a>
        <div class="dates">
            <span>{{ $categoryTranslation->name ?? __('index.blogs.uncategorized') }}</span>
        </div>
    </div>
    <div class="news-content-wrap flex-grow-1 d-flex flex-column">
        @if(isset($showAuthor) && $showAuthor)
            <ul>
                <li>
                    <i class="flaticon-user"></i>
                    {{ $blog->author?->name ?? __('blog/index.author_label') }}
                </li>
            </ul>
        @endif

        <a href="{{ route('blog.show', ['locale' => app()->getLocale(), 'blog' => $translation->slug ?? $blog->id]) }}"
            class="stretched-link">
            <h3>{{ $translation->title ?? __('No translation') }}</h3>
        </a>

        <p>{{ Str::limit($translation->excerpt ?? strip_tags($translation->body ?? ''), 100) }}</p>

        <div class="mt-auto">
            <span class="read-more">
                {{ __('index.blogs.button') }}
                <i class="{{ $flaticonArrow }}"></i>
            </span>
        </div>
    </div>
</div>
