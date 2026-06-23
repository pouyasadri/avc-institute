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
            <x-sections.services :subtitle="__('index.services.subtitle')" :title="__('index.services.title')"
                :items="$servicesList" />
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
    @endphp

    @if(isset($schema))
        <x-seo.structured-data :schema="$schema" />
    @endif
    <x-seo.structured-data :schema="$breadcrumb" />
@endpush