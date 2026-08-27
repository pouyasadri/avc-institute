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
                                    <a href="{{ url($currentLocale . "/consult") }}"
                                        class="d-flex align-items-center text-decoration-none">
                                        <i class='bx bx-time me-2 fs-5 text-primary'></i>
                                        <span>@yield('consultation_text')</span>
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