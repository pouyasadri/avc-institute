@extends('layouts.main')

@php
    $currentLocale = app()->getLocale();
    $isRtl = in_array($currentLocale, ['fa'], true);
    $arrowIcon = $isRtl ? 'flaticon-left-arrow' : 'flaticon-right-arrow';

    // Metadata for SEO
    $pageTitle = __('universities.title');
    $pageKeywords = __('universities.keywords');
    $pageDescription = __('universities.description');

    $universities = [
        ['slug' => 'paris-saclay-university', 'bg' => 'bg-3', 'name' => 'paris_saclay'],
        ['slug' => 'sorbonne-paris-nord', 'bg' => 'bg-4', 'name' => 'sorbonne_paris_nord'],
        ['slug' => 'paris-cite', 'bg' => 'bg-5', 'name' => 'paris_cite'],
        ['slug' => 'paris-4-sorbonne', 'bg' => 'bg-6', 'name' => 'paris_4'],
        ['slug' => 'paris-3', 'bg' => 'bg-7', 'name' => 'paris_3'],
        ['slug' => 'paris-2', 'bg' => 'bg-8', 'name' => 'paris_2'],
        ['slug' => 'lyon-3', 'bg' => 'bg-9', 'name' => 'lyon_3'],
        ['slug' => 'lyon-2', 'bg' => 'bg-10', 'name' => 'lyon_2'],
        ['slug' => 'lyon-1', 'bg' => 'bg-11', 'name' => 'lyon_1'],
        ['slug' => 'pantheon-sorbonne', 'bg' => 'bg-12', 'name' => 'pantheon_sorbonne'],
        ['slug' => 'cote-d-azure', 'bg' => 'bg-13', 'name' => 'nice'],
        ['slug' => 'toulouse', 'bg' => 'bg-14', 'name' => 'toulouse'],
        ['slug' => 'strasbourg', 'bg' => 'bg-15', 'name' => 'strasbourg'],
        ['slug' => 'universite-psl', 'bg' => 'bg-16', 'name' => 'psl'],
        ['slug' => 'ip-paris', 'bg' => 'bg-17', 'name' => 'ip_paris'],
        ['slug' => 'universite-grenoble-alpes', 'bg' => 'bg-18', 'name' => 'grenoble_alpes'],
        ['slug' => 'aix-marseille-university', 'bg' => 'bg-19', 'name' => 'aix_marseille'],
        ['slug' => 'universite-de-bordeaux', 'bg' => 'bg-20', 'name' => 'bordeaux'],
        ['slug' => 'universite-de-lille', 'bg' => 'bg-21', 'name' => 'lille'],
    ];
@endphp

@section('title', $pageTitle)
@section('keywords', $pageKeywords)
@section('description', $pageDescription)

@section('content')
    <div>
        <!-- Start Page Title Area -->
        <header class="page-title-area" role="banner">
            <div class="container">
                <div class="page-title-content">
                    <x-premium-breadcrumb :items="[
            ['url' => url($currentLocale . '/'), 'label' => __('universities.breadcrumb_home')],
            ['label' => __('universities.breadcrumb_universities')]
        ]" />
                    <h1>{{ __('universities.main_heading') }}</h1>
                </div>
            </div>
        </header>

        <!-- Start Introduction Section -->
        <section class="exclusive-area pt-100 pb-70">
            <div class="container">
                <div class="section-title text-center mb-5">
                    <span
                        class="text-uppercase fw-bold text-primary tracking-wider">{{ __('universities.section_title') }}</span>
                    <h2 class="display-6 fw-bold mt-2">{{ __('universities.section_heading') }}</h2>
                    <p class="mx-auto mt-3 text-muted" style="max-width: 800px;">
                        {{ __('universities.section_paragraph') }}
                    </p>
                </div>

                <div class="row g-4">
                    @foreach($universities as $uni)
                        <div class="col-lg-6">
                            <article
                                class="exclusive-wrap rounded-5 shadow-sm overflow-hidden h-100 bg-white border-0 transition-all hover-lift">
                                <div class="row g-0 align-items-center h-100">
                                    <div class="col-md-5">
                                        <div class="exclusive-img {{ $uni['bg'] }} h-100" style="min-height: 250px;"></div>
                                    </div>
                                    <div class="col-md-7">
                                        <div class="exclusive-content p-4">
                                            <h3 class="h4 fw-bold mb-2">{{ __('universities.' . $uni['name'] . '_name') }}</h3>
                                            <div class="rating mb-3">
                                                <span
                                                    class="badge bg-primary-subtle text-primary border border-primary-subtle rounded-pill px-3 py-2">
                                                    <i class="bx bx-award me-1"></i>
                                                    {{ __('universities.world_rank') }}:
                                                    {{ __('universities.' . $uni['name'] . '_rank') }}
                                                </span>
                                            </div>
                                            <p class="text-muted small mb-4">
                                                {{ __('universities.' . $uni['name'] . '_description') }}
                                            </p>
                                            <a href="{{ url($currentLocale . "/universities/" . $uni['slug']) }}"
                                                class="default-btn rounded-pill px-4 transition-all">
                                                {{ __('universities.more_info') }}
                                                <i class="{{ $arrowIcon }}"></i>
                                            </a>
                                        </div>
                                    </div>
                                </div>
                            </article>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <!-- Start Benefits Section -->
        <section class="our-rooms-area-two pb-100 bg-light-subtle pt-100">
            <div class="container">
                <div class="section-title text-center mb-5">
                    <span
                        class="text-uppercase fw-bold text-primary tracking-wider">{{ __('universities.benefits_section_title') }}</span>
                    <h2 class="display-6 fw-bold mt-2">{{ __('universities.benefits_section_heading') }}</h2>
                    <p class="mx-auto mt-3 text-muted" style="max-width: 800px;">
                        {{ __('universities.benefits_section_paragraph') }}
                    </p>
                </div>

                <div class="row g-4 justify-content-center">
                    @php
                        $benefits = [
                            ['icon' => 'cap', 'key' => 'quality_education_system'],
                            ['icon' => 'book', 'key' => 'diverse_academic_fields'],
                            ['icon' => 'globe', 'key' => 'french_language_international_power'],
                            ['icon' => 'money', 'key' => 'scholarships_financial_aid'],
                            ['icon' => 'culture', 'key' => 'living_environment_culture'],
                            ['icon' => 'network', 'key' => 'international_connections'],
                        ];
                    @endphp

                    <style>
                        .benefit-card:hover .benefit-icon svg {
                            color: #ff5d22 !important;
                        }

                        .benefit-card:hover h3 {
                            color: #ff5d22 !important;
                        }

                        .benefit-card:hover p {
                            color: #6c757d !important;
                        }
                    </style>

                    @foreach($benefits as $benefit)
                        <div class="col-lg-4 col-md-6">
                            <div
                                class="benefit-card text-center p-5 rounded-5 shadow-sm bg-white h-100 border-0 transition-all hover-lift">
                                <div class="benefit-icon mb-4 text-primary transition-colors">
                                    @if($benefit['icon'] == 'cap')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M8.211 2.047a.5.5 0 0 0-.422 0l-7.5 3.5a.5.5 0 0 0 .025.917l7.5 3a.5.5 0 0 0 .372 0L14 7.14V13a1 1 0 0 0-1 1v2h3v-2a1 1 0 0 0-1-1V6.739l.686-.275a.5.5 0 0 0 .025-.917l-7.5-3.5ZM8 8.46 1.758 5.965 8 3.052l6.242 2.913L8 8.46Z" />
                                            <path
                                                d="M4.176 9.032a.5.5 0 0 0-.656.327l-.5 1.7a.5.5 0 0 0 .294.605l4.5 1.8a.5.5 0 0 0 .372 0l4.5-1.8a.5.5 0 0 0 .294-.605l-.5-1.7a.5.5 0 0 0-.656-.327L8 10.466 4.176 9.032Zm-.068 1.873.22-.748 3.496 1.311a.5.5 0 0 0 .352 0l3.496-1.311.22.748L8 12.46l-3.892-1.556Z" />
                                        </svg>
                                    @elseif($benefit['icon'] == 'book')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M1 2.828c.885-.37 2.154-.769 3.388-.893 1.33-.134 2.458.063 3.112.752v9.746c-.935-.53-2.12-.603-3.213-.493-1.18.12-2.37.461-3.287.811V2.828zm7.5-.141c.654-.689 1.782-.886 3.112-.752 1.234.124 2.503.523 3.388.893v9.923c-.918-.35-2.107-.692-3.287-.81-1.094-.111-2.278-.039-3.213.492V2.687zM8 1.783C7.015.936 5.587.81 4.287.94c-1.514.153-3.042.672-3.994 1.105A.5.5 0 0 0 0 2.5v11a.5.5 0 0 0 .707.455c.882-.4 2.303-.881 3.68-1.02 1.409-.142 2.59.087 3.223.877a.5.5 0 0 0 .78 0c.633-.79 1.814-1.019 3.222-.877 1.378.139 2.8.62 3.681 1.02A.5.5 0 0 0 16 13.5v-11a.5.5 0 0 0-.293-.455c-.952-.433-2.48-.952-3.994-1.105C10.413.809 8.985.936 8 1.783z" />
                                        </svg>
                                    @elseif($benefit['icon'] == 'globe')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M4.545 6.714 4.11 8H3l1.862-5h1.284L8 8H6.833l-.435-1.286H4.545zm1.634-.736L5.5 3.956h-.049l-.679 2.022H6.18z" />
                                            <path
                                                d="M0 2a2 2 0 0 1 2-2h7a2 2 0 0 1 2 2v3h3a2 2 0 0 1 2 2v7a2 2 0 0 1-2 2H7a2 2 0 0 1-2-2v-3H2a2 2 0 0 1-2-2V2zm2-1a1 1 0 0 0-1 1v7a1 1 0 0 0 1 1h7a1 1 0 0 0 1-1V2a1 1 0 0 0-1-1H2zm7.138 9.995c.193.301.402.583.63.846-.748.575-1.673 1.001-2.768 1.292.178.217.451.635.555.867 1.125-.359 2.08-.844 2.886-1.494.777.665 1.739 1.165 2.93 1.472.133-.254.414-.673.629-.89-1.125-.253-2.057-.694-2.82-1.284.681-.747 1.222-1.651 1.621-2.757H14V8h-3v1.047h.765c-.318.844-.74 1.546-1.272 2.13a6.066 6.066 0 0 1-.415-.492 1.988 1.988 0 0 1-.94.31z" />
                                        </svg>
                                    @elseif($benefit['icon'] == 'money')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor"
                                            viewBox="0 0 16 16">
                                            <path fill-rule="evenodd"
                                                d="M11 15a4 4 0 1 0 0-8 4 4 0 0 0 0 8zm5-4a5 5 0 1 1-10 0 5 5 0 0 1 10 0z" />
                                            <path
                                                d="M9.438 11.944c.047.596.518 1.06 1.363 1.116v.44h.375v-.443c.875-.061 1.386-.529 1.386-1.207 0-.618-.39-.936-1.09-1.1l-.296-.07v-1.2c.376.043.614.248.671.532h.658c-.047-.575-.54-1.024-1.329-1.073V8.5h-.375v.45c-.747.073-1.255.522-1.255 1.158 0 .562.378.92 1.007 1.066l.248.061v1.272c-.384-.058-.639-.27-.696-.563h-.668zm1.36-1.354c-.369-.085-.569-.26-.569-.522 0-.294.216-.514.572-.578v1.1h-.003zm.432.746c.449.104.655.272.655.569 0 .339-.257.571-.709.614v-1.195l.054.012z" />
                                            <path
                                                d="M1 0a1 1 0 0 0-1 1v8a1 1 0 0 0 1 1h4.083c.058-.344.145-.678.258-1H3a2 2 0 0 0-2-2V3a2 2 0 0 0 2-2h10a2 2 0 0 0 2-2v3.528c.38.34.717.728 1 1.154V1a1 1 0 0 0-1-1H1z" />
                                            <path d="M9.998 5.083 10 5a2 2 0 1 0-3.132 1.65 5.982 5.982 0 0 1 3.13-1.567z" />
                                        </svg>
                                    @elseif($benefit['icon'] == 'culture')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M15 14s1 0 1-1-1-4-5-4-5 3-5 4 1 1 1 1h8Zm-7.978-1A.261.261 0 0 1 7 12.996c.001-.264.167-1.03.76-1.72C8.312 10.629 9.282 10 11 10c1.717 0 2.687.63 3.24 1.276.593.69.758 1.457.76 1.72l-.008.002a.274.274 0 0 1-.014.002H7.022ZM11 7a2 2 0 1 0 0-4 2 2 0 0 0 0 4Zm3-2a3 3 0 1 1-6 0 3 3 0 0 1 6 0ZM6.936 9.28a5.88 5.88 0 0 0-1.23-.247A7.35 7.35 0 0 0 5 9c-4 0-5 3-5 4 0 .667.333 1 1 1h4.216A2.238 2.238 0 0 1 5 13c0-1.01.377-2.042 1.09-2.904.243-.294.526-.569.846-.816ZM4.92 10A5.493 5.493 0 0 0 4 13H1c0-.26.164-1.03.76-1.724.545-.636 1.492-1.256 3.16-1.275ZM1.5 5.5a3 3 0 1 1 6 0 3 3 0 0 1-6 0Zm3-2a2 2 0 1 0 0 4 2 2 0 0 0 0-4Z" />
                                        </svg>
                                    @elseif($benefit['icon'] == 'network')
                                        <svg xmlns="http://www.w3.org/2000/svg" width="48" height="48" fill="currentColor"
                                            viewBox="0 0 16 16">
                                            <path
                                                d="M0 8a8 8 0 1 1 16 0A8 8 0 0 1 0 8zm7.5-6.923c-.67.204-1.335.82-1.887 1.855A7.97 7.97 0 0 0 5.145 4H7.5V1.077zM4.09 4a9.267 9.267 0 0 1 .64-1.539 6.7 6.7 0 0 1 .597-.933A7.025 7.025 0 0 0 2.255 4H4.09zm-.582 3.5c.03-.877.138-1.718.312-2.5H1.674a6.958 6.958 0 0 0-.656 2.5h2.49zM4.847 5a12.5 12.5 0 0 0-.338 2.5H7.5V5H4.847zM8.5 5v2.5h2.99a12.495 12.495 0 0 0-.337-2.5H8.5zM4.51 8.5a12.5 12.5 0 0 0 .337 2.5H7.5V8.5H4.51zm3.99 0V11h2.653c.187-.765.306-1.608.338-2.5H8.5zM5.145 12c.138.386.295.744.468 1.068.552 1.035 1.218 1.65 1.887 1.855V12H5.145zm.182 2.472a6.696 6.696 0 0 1-.597-.933A9.268 9.268 0 0 1 4.09 12H2.255a7.024 7.024 0 0 0 3.072 2.472zM3.82 11a13.652 13.652 0 0 1-.312-2.5h-2.49c.062.89.291 1.733.656 2.5H3.82zm6.853 3.472A7.024 7.024 0 0 0 13.745 12H11.91a9.27 9.27 0 0 1-.64 1.539 6.688 6.688 0 0 1-.597.933zM8.5 12v2.923c.67-.204 1.335-.82 1.887-1.855.173-.324.33-.682.468-1.068H8.5zm3.68-1h2.146c.365-.767.594-1.61.656-2.5h-2.49a13.65 13.65 0 0 1-.312 2.5zm2.802-3.5a6.959 6.959 0 0 0-.656-2.5H12.18c.174.782.282 1.623.312 2.5h2.49zM11.27 2.461c.247.464.462.98.64 1.539h1.835a7.024 7.024 0 0 0-3.072-2.472c.218.284.418.598.597.933zM10.855 4a7.966 7.966 0 0 0-.468-1.068C9.835 1.897 9.17 1.282 8.5 1.077V4h2.355z" />
                                        </svg>
                                    @endif
                                </div>
                                <h3 class="h5 fw-bold mb-3">{{ __('universities.' . $benefit['key']) }}</h3>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </section>

        <x-sections.services :subtitle="__('universities.services_section_title')"
            :title="__('universities.services_section_heading')" :items="[
            ['title' => __('universities.residence_consultation'), 'description' => __('universities.residence_consultation_description')],
            ['title' => __('universities.resume_motivation_writing'), 'description' => __('universities.resume_motivation_description')],
            ['title' => __('universities.administrative_ease'), 'description' => __('universities.administrative_ease_description')],
            ['title' => __('universities.document_translation'), 'description' => __('universities.document_translation_description')],
            ['title' => __('universities.educational_consultation'), 'description' => __('universities.educational_consultation_description')],
            ['title' => __('universities.accommodation_booking'), 'description' => __('universities.accommodation_booking_description')],
            ['title' => __('universities.admission_acceptance'), 'description' => __('universities.admission_acceptance_description')],
            ['title' => __('universities.administrative_support_france'), 'description' => __('universities.administrative_support_description')],
            ['title' => __('universities.legal_services'), 'description' => __('universities.legal_services_description')],
        ]" />
    </div>
@endsection

@push("json")
    @php
        $breadcrumb = \App\Services\StructuredData\BreadcrumbSchema::fromArray([
            ['name' => __('universities.breadcrumb_home'), 'url' => url($currentLocale . '/')],
            ['name' => __('universities.breadcrumb_universities'), 'url' => url($currentLocale . '/universities')],
        ]);

        $collectionPage = new \App\Services\StructuredData\CollectionPageSchema(
            url()->current(),
            $pageTitle,
            $pageDescription,
            $currentLocale
        );

        $list = new \App\Services\StructuredData\ItemListSchema($pageTitle);
        $position = 1;
        foreach ($universities as $uni) {
            $list->addItem(
                $position,
                url($currentLocale . '/universities/' . $uni['slug']),
                __('universities.' . $uni['name'] . '_name')
            );
            $position++;
        }
    @endphp

    <x-seo.structured-data :schema="$collectionPage" />
    <x-seo.structured-data :schema="$breadcrumb" />
    <x-seo.structured-data :schema="$list" />
@endpush