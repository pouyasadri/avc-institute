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

                            @if(isset($serviceDetails['key_facts']) && is_array($serviceDetails['key_facts']))
                                <div class="key-facts-card p-4 rounded-4 mb-4 bg-light border border-primary-subtle shadow-sm">
                                    <div class="d-flex align-items-center mb-3">
                                        <i class="bx bx-check-shield fs-4 text-primary me-2"></i>
                                        <h3 class="h6 fw-bold mb-0 text-primary">{{ __('services.key_facts_title') }}</h3>
                                    </div>
                                    <div class="row g-3">
                                        @foreach($serviceDetails['key_facts'] as $fact)
                                            <div class="col-12 col-sm-6">
                                                <div class="p-3 bg-white rounded-3 border h-100 shadow-xs">
                                                    <div class="text-muted small mb-1">{{ $fact['label'] }}</div>
                                                    <div class="fw-bold text-dark">{{ $fact['value'] }}</div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            @if(isset($serviceDetails['content']) && is_array($serviceDetails['content']))
                                @foreach($serviceDetails['content'] as $paragraph)
                                    <p class="mb-3">{{ $paragraph }}</p>
                                @endforeach
                            @endif

                            {{-- Mid-content Contextual CTA Banner --}}
                            <div class="service-mid-cta p-4 rounded-4 my-4 text-white bg-primary shadow-sm">
                                <div class="row align-items-center g-3">
                                    <div class="col-lg-8">
                                        <h3 class="h5 fw-bold text-white mb-2">{{ __('services.cta_banner_title') }}</h3>
                                        <p class="small text-white-50 mb-0">{{ __('services.cta_banner_desc') }}</p>
                                    </div>
                                    <div class="col-lg-4 text-lg-end">
                                        <a href="{{ url($currentLocale . '/consult?service=' . $slug) }}" class="btn btn-light rounded-pill px-4 py-2 fw-bold text-primary shadow-sm">
                                            {{ __('services.cta_banner_button') }}
                                            <i class="{{ $arrowIcon }} ms-1"></i>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            @if($slug === 'university-application' && $currentLocale === 'fa')
                                <h3 class="h5 fw-bold mt-5 mb-4 text-primary">نحوه درخواست دانشگاه در فرانسه — گام به گام (کمپوس فرانسه ۲۰۲۶)</h3>
                                <div class="timeline-steps position-relative mb-4">
                                    <div class="row g-4">
                                        <div class="col-12 col-md-4">
                                            <div class="p-3 bg-light rounded-4 h-100 position-relative border-top border-primary border-4 shadow-sm">
                                                <div class="step-num bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold mb-3" style="width:30px; height:30px;">۱</div>
                                                <h4 class="h6 fw-bold mb-2">انتخاب رشته و دانشگاه</h4>
                                                <p class="small text-muted mb-0">بررسی کامل رشته‌های مورد علاقه در پورتال کمپوس فرانسه و آماده‌سازی مدرک زبان فرانسوی (DELF B2/TCF) یا انگلیسی (IELTS).</p>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="p-3 bg-light rounded-4 h-100 position-relative border-top border-primary border-4 shadow-sm">
                                                <div class="step-num bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold mb-3" style="width:30px; height:30px;">۲</div>
                                                <h4 class="h6 fw-bold mb-2">ثبت‌نام در کمپوس فرانسه</h4>
                                                <p class="small text-muted mb-0">پر کردن اطلاعات شخصی و آپلود ترجمه رسمی مدارک، رزومه استاندارد و انگیزه‌نامه (Lettre de Motivation) شخصی‌سازی شده در پورتال EEF.</p>
                                            </div>
                                        </div>
                                        <div class="col-12 col-md-4">
                                            <div class="p-3 bg-light rounded-4 h-100 position-relative border-top border-primary border-4 shadow-sm">
                                                <div class="step-num bg-primary text-white rounded-circle d-flex align-items-center justify-content-center fw-bold mb-3" style="width:30px; height:30px;">۳</div>
                                                <h4 class="h6 fw-bold mb-2">مصاحبه و اخذ پذیرش</h4>
                                                <p class="small text-muted mb-0">شرکت در مصاحبه حضوری/آنلاین کمپوس فرانسه برای دفاع از پروژه تحصیلی و سپس دریافت پاسخ نهایی پذیرش از سوی دانشگاه‌ها.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
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
                            <a href="{{ url($currentLocale . '/consult?service=' . $slug) }}" class="default-btn w-100 text-center">
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

        {{-- Mobile Sticky Conversion Bar (Targeting 94% mobile users) --}}
        <div class="mobile-sticky-cta d-md-none fixed-bottom bg-white border-top shadow-lg py-2 px-3" style="z-index: 1050;">
            <div class="d-flex align-items-center justify-content-between">
                <div class="me-2 text-truncate">
                    <span class="d-block small text-muted text-truncate" style="max-width: 190px;">{{ $pageTitle }}</span>
                    <strong class="small text-primary">{{ __('services.sticky_mobile_cta') }}</strong>
                </div>
                <a href="{{ url($currentLocale . '/consult?service=' . $slug) }}" class="btn btn-primary btn-sm rounded-pill px-3 py-2 text-nowrap fw-bold shadow-sm">
                    {{ __('index.video.button') ?? 'Book Consultation' }}
                    <i class="{{ $arrowIcon }}"></i>
                </a>
            </div>
        </div>
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
