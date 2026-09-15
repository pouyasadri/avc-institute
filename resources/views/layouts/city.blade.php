@extends('layouts.main')

@php
    $currentLocale = app()->getLocale();
    $isRtl = in_array($currentLocale, ['fa'], true);

    $cityContent = trim($__env->yieldContent('city_content'));
    $tocData = \App\Helpers\TocHelper::generate($cityContent);
    $cityContentWithIds = $tocData['content'];
    $toc = $tocData['toc'];
@endphp

@section('content')
    <!-- Page Title Area -->
    <header class="page-title-area @yield('header_class')" role="banner">
        <div class="container">
            <div class="page-title-content">
                <x-premium-breadcrumb :items="[
            ['url' => url($currentLocale . '/'), 'label' => __('layout.home') ?? 'Home'],
            ['url' => url($currentLocale . '/cities'), 'label' => __('cities.breadcrumb_cities')],
            ['label' => trim($__env->yieldContent('breadcrumb_current'))]
        ]" />
                <h1>@yield('page_title_heading')</h1>
            </div>
        </div>
    </header>

    <!-- Content Area -->
    <section class="service-details-area ptb-100">
        <div class="container" id="mydiv">
            <div class="row g-4">
                <!-- Sidebar -->
                <aside class="col-lg-4 order-2 order-lg-1">
                    <div class="service-sidebar-area">
                        <!-- Table of Contents -->
                        <x-toc :toc="$toc" :title="trim($__env->yieldContent('toc_title', __('city/paris.table_of_contents')))" />

                        <!-- Contact Sidebar -->
                        <div class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4 border-0">
                            <h4 class="widget-title h5 fw-bold mb-3 border-bottom pb-2">
                                @yield('contact_title', __('city/paris.contact_us'))</h4>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <a href="{{ url($currentLocale . "/consult?service=student-visa") }}"
                                        class="d-flex align-items-center text-decoration-none"
                                        title="{{ $currentLocale === 'fa' ? 'مشاوره مهاجرت و اخذ اقامت فرانسه با موسسه A.V.C' : 'Consultation' }}">
                                        <i class='bx bx-time me-2 fs-5 text-primary'></i>
                                        <span class="fw-semibold">
                                            @if($currentLocale === 'fa')
                                                رزرو وقت در موسسه مهاجرتی فرانسه (A.V.C)
                                            @else
                                                @yield('consultation_text')
                                            @endif
                                        </span>
                                    </a>
                                </li>
                                <li>
                                    <a href="mailto:info@applyvipconseil.com"
                                        class="d-flex align-items-center text-decoration-none">
                                        <i class='bx bx-envelope me-2 fs-5 text-primary'></i>
                                        <span>info@applyvipconseil.com</span>
                                    </a>
                                </li>
                            </ul>
                            <div class="mt-3 pt-3 border-top">
                                <a href="{{ url($currentLocale . "/consult?service=student-visa") }}"
                                   class="btn btn-outline-primary btn-sm rounded-pill w-100 fw-bold d-flex align-items-center justify-content-center gap-1">
                                    <i class="bx bx-calendar-plus"></i>
                                    <span>{{ __('cta.city.sidebar_cta_btn') }}</span>
                                </a>
                            </div>
                        </div>

                        <!-- Related Services Widget -->
                        <div class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4 border-0">
                            <h4 class="widget-title h6 fw-bold mb-3 border-bottom pb-2">
                                <i class="bx bx-briefcase-alt text-primary me-2"></i>
                                {{ __('services.other_services') ?? 'خدمات مهاجرتی مرتبط' }}
                            </h4>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <a href="{{ route('services.show', ['locale' => $currentLocale, 'slug' => 'housing-assistance']) }}" class="text-decoration-none text-dark d-flex align-items-center small">
                                        <i class="bx {{ $isRtl ? 'bx-chevron-left' : 'bx-chevron-right' }} text-primary me-1"></i>
                                        <span>{{ __('services.housing-assistance.title') ?? 'اجاره مسکن و کمک‌هزینه CAF' }}</span>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('services.show', ['locale' => $currentLocale, 'slug' => 'student-visa']) }}" class="text-decoration-none text-dark d-flex align-items-center small">
                                        <i class="bx {{ $isRtl ? 'bx-chevron-left' : 'bx-chevron-right' }} text-primary me-1"></i>
                                        <span>{{ __('services.student-visa.title') ?? 'ویزای تحصیلی فرانسه' }}</span>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('services.show', ['locale' => $currentLocale, 'slug' => 'residence-permit']) }}" class="text-decoration-none text-dark d-flex align-items-center small">
                                        <i class="bx {{ $isRtl ? 'bx-chevron-left' : 'bx-chevron-right' }} text-primary me-1"></i>
                                        <span>{{ __('services.residence-permit.title') ?? 'کارت اقامت و تمدید' }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('services.show', ['locale' => $currentLocale, 'slug' => 'arrival-support']) }}" class="text-decoration-none text-dark d-flex align-items-center small">
                                        <i class="bx {{ $isRtl ? 'bx-chevron-left' : 'bx-chevron-right' }} text-primary me-1"></i>
                                        <span>{{ __('services.arrival-support.title') ?? 'پشتیبانی بدو ورود فرانسه' }}</span>
                                    </a>
                                </li>
                            </ul>
                        </div>

                        <!-- Useful Links -->
                        <div class="sidebar-widgets">
                            @yield('useful_links')
                        </div>
                    </div>
                </aside>

                <!-- Main Content -->
                <div class="col-lg-8 order-1 order-lg-2">
                    <article class="service-details-wrap p-4 p-md-5 rounded-5 shadow-sm bg-white border-0">
                        <div class="article-content">
                            {!! $cityContentWithIds !!}

                            {{-- Interactive Student Budget & Visa Proof Calculator --}}
                            <x-calculator.student-budget :initialCity="$cityName ?? 'paris'" />

                            {{-- Immigration, Settlement & Visa Evaluation CTA Banner --}}
                            <x-cta.city-evaluation :cityName="$cityName ?? ''" />
                        </div>

                        <!-- Contact Form -->
                        <div class="ask-question mt-5 pt-5 border-top">
                            <h3 class="h4 fw-bold mb-4">@yield('ask_question_title')</h3>
                            <x-forms.university-contact pageType="city" :pageName="$cityName ?? 'unknown'" />
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

@endsection

@push('json')
    @php
        $citySlug = $cityName ?? request()->route('city') ?? request()->segment(3) ?? '';
        $cityFaqKey = "city/{$citySlug}.faq_items";
        if (Lang::has($cityFaqKey)) {
            $cityFaqItems = __($cityFaqKey);
            if (is_array($cityFaqItems) && !empty($cityFaqItems)) {
                $cityFaqSchema = (new \App\Services\StructuredData\FAQSchema())->addQuestions($cityFaqItems);
            }
        }
    @endphp

    @if(isset($cityFaqSchema))
        <x-seo.structured-data :schema="$cityFaqSchema" />
    @endif
@endpush