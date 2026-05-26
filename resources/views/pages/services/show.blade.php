@extends('layouts.main')

@php
    $currentLocale = app()->getLocale();
    $isRtl = in_array($currentLocale, ['fa'], true);
    $arrowIcon = $isRtl ? 'flaticon-left-arrow' : 'flaticon-right-arrow';

    // Metadata for SEO
    $pageTitle = $serviceDetails['title'] ?? 'Service';
    $pageDescription = $serviceDetails['description'] ?? '';
@endphp

@section('title', $pageTitle . ' - A.V.C Institute')
@section('description', $pageDescription)

@section('content')
    <div>
        <!-- Start Page Title Area -->
        <header class="page-title-area" role="banner">
            <div class="container">
                <div class="page-title-content">
                    <x-premium-breadcrumb :items="[
            ['url' => url($currentLocale . '/'), 'label' => __('consult.breadcrumb_home') ?? 'Home'],
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
                            <h2 class="mb-4">{{ $pageTitle }}</h2>

                            @if(isset($serviceDetails['content']) && is_array($serviceDetails['content']))
                                @foreach($serviceDetails['content'] as $paragraph)
                                    <p class="mb-3">{{ $paragraph }}</p>
                                @endforeach
                            @endif

                            @if(isset($serviceDetails['benefits']) && is_array($serviceDetails['benefits']))
                                <h3 class="mt-5 mb-4">{{ __('index.facilities.subtitle') ?? 'Benefits' }}</h3>
                                <ul class="list-unstyled">
                                    @foreach($serviceDetails['benefits'] as $benefit)
                                        <li class="mb-3 d-flex align-items-center">
                                            <i class="bx bx-check-circle text-primary me-2"></i>
                                            <span>{{ $benefit }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            @endif
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