@extends('layouts.main')

@php
    $currentLocale = app()->getLocale();
    $seoService = app(\App\Services\SeoService::class);
    $seoService->setTitle(__('services.meta.title') ?? ($pageTitle . ' - A.V.C Institute'), false)
               ->setDescription($pageDescription ?? __('services.meta.description') ?? 'Our services')
               ->setKeywords($pageKeywords ?? __('services.meta.keywords') ?? '')
               ->setLocale($currentLocale);
@endphp

@section('title', __('services.meta.title') ?? ($pageTitle . ' - A.V.C Institute'))
@section('description', $pageDescription ?? __('services.meta.description') ?? 'Our services')
@section('keywords', $pageKeywords ?? __('services.meta.keywords') ?? '')

@section('content')
    <div>
        <!-- Start Page Title Area -->
        <header class="page-title-area" role="banner">
            <div class="container">
                <div class="page-title-content">
                    <x-premium-breadcrumb :items="[
            ['url' => url($locale . '/'), 'label' => __('consult.breadcrumb_home') ?? 'Home'],
            ['label' => $pageTitle]
        ]" />
                    <h1>{{ $pageTitle }}</h1>
                </div>
            </div>
        </header>
        <!-- End Page Title Area -->

        <!-- Start Services Area -->
        <div class="pt-100 pb-70">
            <div class="container mb-5">
                <div class="row justify-content-center text-center">
                    <div class="col-lg-10">
                        <p class="lead text-muted">
                            {{ $pageDescription }}
                        </p>
                    </div>
                </div>
            </div>

            <x-sections.services :subtitle="__('index.services.subtitle')" :title="__('index.services.title')"
                :items="$servicesList" />

            <div class="container mt-5">
                <div class="p-4 p-md-5 rounded-5 bg-light border border-primary-subtle text-center shadow-sm">
                    <h3 class="h4 fw-bold mb-3">{{ __('services.cta_banner_title') }}</h3>
                    <p class="text-muted mb-4 max-w-700 mx-auto">{{ __('services.cta_banner_desc') }}</p>
                    <a href="{{ url($locale . '/consult') }}" class="default-btn rounded-pill px-5">
                        {{ __('services.cta_banner_button') }}
                        <i class="{{ in_array($locale, ['fa'], true) ? 'flaticon-left-arrow' : 'flaticon-right-arrow' }}"></i>
                    </a>
                </div>
            </div>
        </div>
        <!-- End Services Area -->
    </div>
@endsection

@push('json')
    @php
        $breadcrumb = \App\Services\StructuredData\BreadcrumbSchema::fromArray([
            ['name' => __('consult.breadcrumb_home') ?? 'Home', 'url' => url($locale . '/')],
            ['name' => $pageTitle, 'url' => request()->url()],
        ]);

        $itemListSchema = new \App\Services\StructuredData\ItemListSchema($pageTitle);
        if (is_array($servicesList)) {
            $pos = 1;
            foreach ($servicesList as $s) {
                if (isset($s['slug']) && isset($s['title'])) {
                    $itemListSchema->addItem(
                        $pos++,
                        route('services.show', ['locale' => $locale, 'slug' => $s['slug']]),
                        $s['title']
                    );
                }
            }
        }
    @endphp

    @if(isset($schema))
        <x-seo.structured-data :schema="$schema" />
    @endif
    <x-seo.structured-data :schema="$breadcrumb" />
    <x-seo.structured-data :schema="$itemListSchema" />
@endpush