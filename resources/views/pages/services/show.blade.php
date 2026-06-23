@extends('layouts.main')

@php
    $currentLocale = app()->getLocale();
    $isRtl = in_array($currentLocale, ['fa'], true);
    $arrowIcon = $isRtl ? 'flaticon-left-arrow' : 'flaticon-right-arrow';

    // Metadata for SEO
    $pageTitle = $serviceDetails['title'] ?? 'Service';
    $pageDescription = $serviceDetails['description'] ?? '';
    $pageKeywords = $serviceDetails['meta_keywords'] ?? '';

    $seoService = app(\App\Services\SeoService::class);
    $seoService->setTitle($pageTitle . ' - A.V.C Institute', false)
               ->setDescription($pageDescription)
               ->setKeywords($pageKeywords)
               ->setLocale($currentLocale);

    $featuredCities = ['paris', 'lyon', 'strasbourg'];
    $featuredUniversities = [
        ['slug' => 'paris-saclay-university', 'name_key' => 'paris_saclay'],
        ['slug' => 'sorbonne-paris-nord', 'name_key' => 'sorbonne_paris_nord'],
        ['slug' => 'lyon-1', 'name_key' => 'lyon_1'],
    ];
@endphp

@section('title', $pageTitle . ' - A.V.C Institute')
@section('description', $pageDescription)
@section('keywords', $pageKeywords)

@section('content')
    <div>
        <!-- Start Page Title Area -->
        <header class="page-title-area" role="banner">
            <div class="container">
                <div class="page-title-content">
                    <x-premium-breadcrumb :items="[
            ['url' => url($currentLocale . '/'), 'label' => __('consult.breadcrumb_home') ?? 'Home'],
            ['url' => url($currentLocale . '/services'), 'label' => __('layout.nav.services') ?? 'Services'],
            ['label' => $pageTitle]
        ]" />
                    <h1>{{ $pageTitle }}</h1>
                </div>
            </div>
        </header>
        <!-- End Page Title Area -->

        <!-- Start Service Details Area -->
        <section class="service-details-area ptb-100">
            <div class="container">
                <div class="row">
                    <div class="col-lg-8 col-md-12">
                        <div class="service-details-desc">
                            <h2 class="h4 mb-4 text-muted">{{ $pageDescription }}</h2>

                            @if(isset($serviceDetails['content']) && is_array($serviceDetails['content']))
                                @foreach($serviceDetails['content'] as $paragraph)
                                    <p class="mb-3">{{ $paragraph }}</p>
                                @endforeach
                            @endif

                            @if(isset($serviceDetails['benefits']) && is_array($serviceDetails['benefits']))
                                <h3 class="mt-5 mb-4">{{ __('services.benefits_heading') ?? 'Why Choose This Service' }}</h3>
                                <ul class="list-unstyled">
                                    @foreach($serviceDetails['benefits'] as $benefit)
                                        <li class="mb-3 d-flex align-items-center">
                                            <i class="bx bx-check-circle text-primary me-2"></i>
                                            <span>{{ $benefit }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif

                            <section class="mt-5 pt-4 border-top">
                                <h3 class="h5 fw-bold mb-3">{{ __('services.related_resources') ?? 'Related Resources' }}</h3>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light h-100">
                                            <h4 class="h6 fw-bold mb-2">{{ __('services.related_cities') ?? 'Popular Cities' }}</h4>
                                            <ul class="list-unstyled mb-0">
                                                @foreach($featuredCities as $citySlug)
                                                    <li class="mb-2">
                                                        <a href="{{ route('cities.' . $citySlug, ['locale' => $currentLocale]) }}" class="text-decoration-none">
                                                            {{ __('cities.' . $citySlug . '_title') }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                    <div class="col-md-6">
                                        <div class="p-3 rounded-4 bg-light h-100">
                                            <h4 class="h6 fw-bold mb-2">{{ __('services.related_universities') ?? 'Top Universities' }}</h4>
                                            <ul class="list-unstyled mb-0">
                                                @foreach($featuredUniversities as $university)
                                                    <li class="mb-2">
                                                        <a href="{{ route('universities.' . $university['slug'], ['locale' => $currentLocale]) }}" class="text-decoration-none">
                                                            {{ __('universities.' . $university['name_key'] . '_name') }}
                                                        </a>
                                                    </li>
                                                @endforeach
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                            </section>
                        </div>
                    </div>
                    <div class="col-lg-4 col-md-12">
                        <div class="service-sidebar-widget rounded-4 p-4 bg-light-subtle shadow-sm mb-4">
                            <h3 class="h5 fw-bold mb-3">{{ __('index.video.button') ?? 'Contact Us' }}</h3>
                            <p class="text-muted small mb-4">{{ __('index.video.p2') ?? 'Contact us for more details.' }}
                            </p>
                            <a href="{{ url($currentLocale . '/consult') }}" class="default-btn w-100 text-center">
                                {{ __('index.video.button') ?? 'Book Consultation' }}
                                <i class="{{ $arrowIcon }}"></i>
                            </a>
                        </div>

                        {{-- Other Services Widget --}}
                        <div class="service-sidebar-widget rounded-4 p-4 bg-white shadow-sm">
                            <h3 class="h5 fw-bold mb-3">{{ __('services.other_services') ?? 'Other Services' }}</h3>
                            <ul class="list-unstyled mb-0">
                                @php
                                    $allServices = __('index.services.items');
                                    $otherServices = collect($allServices)->reject(fn($s) => ($s['slug'] ?? '') === $slug)->take(5);
                                @endphp
                                @foreach($otherServices as $s)
                                    <li class="mb-2">
                                        <a href="{{ route('services.show', ['locale' => $currentLocale, 'slug' => $s['slug']]) }}" 
                                           class="text-decoration-none text-dark d-flex align-items-center">
                                            <i class="bx bx-chevron-left me-2 text-primary"></i>
                                            <span>{{ $s['title'] }}</span>
                                        </a>
                                    </li>
                                @endforeach
                            </ul>
                        </div>

                        <div class="service-sidebar-widget rounded-4 p-4 bg-white shadow-sm mt-4">
                            <h3 class="h5 fw-bold mb-3">{{ __('services.more_learning') ?? 'Learn More' }}</h3>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <a href="{{ route('blog.index', ['locale' => $currentLocale]) }}" class="text-decoration-none text-dark">
                                        {{ __('services.related_blog') ?? 'Read immigration blog articles' }}
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('universities.index', ['locale' => $currentLocale]) }}" class="text-decoration-none text-dark">
                                        {{ __('services.related_universities_index') ?? 'Explore university guides' }}
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('cities.index', ['locale' => $currentLocale]) }}" class="text-decoration-none text-dark">
                                        {{ __('services.related_cities_index') ?? 'Explore city guides' }}
                                    </a>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Service Details Area -->

        @if(isset($serviceDetails['faq']) && is_array($serviceDetails['faq']))
            @php
                $faqItems = collect($serviceDetails['faq'])->map(function ($faq) {
                    return [
                        'question' => $faq['q'],
                        'answer' => $faq['a']
                    ];
                })->toArray();
            @endphp
            <x-sections.faq :title="__('faq.title') ?? 'Frequently Asked Questions'" :items="$faqItems" />
        @endif
    </div>
@endsection

@push('json')
    @php
        $breadcrumb = \App\Services\StructuredData\BreadcrumbSchema::fromArray([
            ['name' => __('consult.breadcrumb_home') ?? 'Home', 'url' => url($currentLocale . '/')],
            ['name' => __('layout.nav.services') ?? 'Services', 'url' => url($currentLocale . '/services')],
            ['name' => $pageTitle, 'url' => request()->url()],
        ]);
    @endphp

    @if(isset($schema))
        <x-seo.structured-data :schema="$schema" />
    @endif
    <x-seo.structured-data :schema="$breadcrumb" />

    {{-- FAQ rich results: unlocks expandable Q&A in Google SERPs for Persian service queries --}}
    @if(isset($faqSchema) && $faqSchema)
        <x-seo.structured-data :schema="$faqSchema" />
    @endif
@endpush
