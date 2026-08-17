@extends('layouts.main')

@section('title', __('blog/index.title'))
@section('keywords', __('blog/index.keywords'))
@section('description', __('blog/index.description'))

@section('content')
    <!-- Start Page Title Area -->
    <header class="page-title-area" role="banner">
        <div class="container">
            <div class="page-title-content">
                <x-premium-breadcrumb :items="[
            ['url' => route('index', ['locale' => app()->getLocale()]), 'label' => __('blog/index.breadcrumb_home')],
            ['label' => __('blog/index.breadcrumb_blogs')]
        ]" />
                <h1>{{ __('blog/index.main_heading') }}</h1>
            </div>
        </div>
    </header>
    <!-- End Page Title Area -->

    <!-- Start News Area -->
    <section class="news-area ptb-100">
        <div class="container">
            <div class="section-title">
                <span>{{ __('blog/index.section_title') }}</span>
                <h2>{{ __('blog/index.section_heading') }}</h2>
            </div>
            <div class="row">
                @forelse($blogs as $blog)
                    <div class="col-lg-4 col-md-6 mb-4">
                        <x-blog-card :blog="$blog" :showAuthor="true" />
                    </div>
                @empty
                    <div class="col-12">
                        <div class="alert alert-info" role="alert">
                            {{-- Prefer a locale-specific message if available, otherwise fall back to a generic one. --}}
                            {{ __('blog/index.no_blogs_in_locale', ['locale' => $locale]) ?: __('blog/index.no_blogs_found') }}
                        </div>
                    </div>
                @endforelse
            </div>

            <div class="col-lg-12 col-md-12">
                <x-ui.pagination :items="$blogs" />
            </div>
        </div>
    </section>
    <!-- End News Area -->
@endsection

@push('json')
    @php
        $currentLocale = app()->getLocale();
        $pageTitle = __('blog/index.title');
        $pageDescription = __('blog/index.description');

        $breadcrumb = \App\Services\StructuredData\BreadcrumbSchema::fromArray([
            ['name' => __('blog/index.breadcrumb_home'), 'url' => route('index', ['locale' => $currentLocale])],
            ['name' => __('blog/index.breadcrumb_blogs'), 'url' => route('blog.index', ['locale' => $currentLocale])],
        ]);

        $collectionPage = new \App\Services\StructuredData\CollectionPageSchema(
            url()->current(),
            $pageTitle,
            $pageDescription,
            $currentLocale
        );

        $list = new \App\Services\StructuredData\ItemListSchema($pageTitle);
        $position = 1;
        foreach ($blogs as $blogItem) {
            $blogTranslation = $blogItem->translations->firstWhere('locale', $currentLocale) ?? $blogItem->translations->first();
            if ($blogTranslation) {
                $list->addItem(
                    $position,
                    route('blog.show', ['locale' => $currentLocale, 'blog' => $blogTranslation->slug ?? $blogItem->id]),
                    $blogTranslation->title,
                    $blogItem->main_image ? asset($blogItem->main_image_url) : null
                );
                $position++;
            }
        }
    @endphp

    <x-seo.structured-data :schema="$collectionPage" />
    <x-seo.structured-data :schema="$breadcrumb" />
    <x-seo.structured-data :schema="$list" />
@endpush