@extends('layouts.main')

@php
    $currentLocale = app()->getLocale();
    $isRtl = in_array($currentLocale, ['fa'], true);

    // Map each university slug to its city page slug (only cities that have a page)
    $universityCityMap = [
        'paris-saclay-university'   => 'paris',
        'sorbonne-paris-nord'       => 'paris',
        'paris-cite'                => 'paris',
        'paris-4-sorbonne'          => 'paris',
        'paris-3'                   => 'paris',
        'paris-2'                   => 'paris',
        'pantheon-sorbonne'         => 'paris',
        'universite-psl'            => 'paris',
        'ip-paris'                  => 'paris',
        'sciences-po'               => 'paris',
        'lyon-1'                    => 'lyon',
        'lyon-2'                    => 'lyon',
        'lyon-3'                    => 'lyon',
        'cote-d-azure'              => 'nice',
        'toulouse'                  => 'toulouse',
        'strasbourg'                => 'strasbourg',
        'universite-grenoble-alpes' => 'grenoble',
        'universite-de-bordeaux'    => 'bordeaux',
        'aix-marseille-university'  => 'marseille',
        'universite-de-montpellier' => 'montpellier',
        // Universities in cities with no dedicated page return null
        'universite-de-lille'       => null,
    ];
    $universitySlug = $universityName ?? '';
    $linkedCity = $universityCityMap[$universitySlug] ?? null;

    $universityContent = trim($__env->yieldContent('university_content'));
    $tocData = \App\Helpers\TocHelper::generate($universityContent);
    $universityContentWithIds = $tocData['content'];
    $toc = $tocData['toc'];
@endphp

@section('content')
    <!-- Page Title Area -->
    <header class="page-title-area @yield('header_class')" role="banner">
        <div class="container">
            <div class="page-title-content">
                <x-premium-breadcrumb :items="[
            ['url' => url($currentLocale . '/'), 'label' => __('layout.home') ?? 'Home'],
            ['url' => url($currentLocale . '/universities'), 'label' => __('universities.breadcrumb_universities')],
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
                        <x-toc :toc="$toc" :title="trim($__env->yieldContent('toc_title', 'Table of Contents'))" />

                        <!-- Contact Sidebar -->
                        <div class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4 border-0">
                            <h4 class="widget-title h5 fw-bold mb-3 border-bottom pb-2">
                                @yield('contact_title', 'Contact Us')</h4>
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

                        {{-- Related Universities --}}
                        <x-university.related :currentSlug="$universitySlug" />

                        {{-- City Guide Link (auto-mapped from university slug) --}}
                        @if($linkedCity)
                            <div class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4 border-0">
                                <h4 class="widget-title h5 fw-bold mb-3 border-bottom pb-2">
                                    {{ __('universities.city_guide') }}
                                </h4>
                                <a href="{{ route('cities.' . $linkedCity, ['locale' => $currentLocale]) }}"
                                   class="d-flex align-items-center text-decoration-none text-dark">
                                    <i class='bx bx-map-alt me-2 fs-5 text-primary'></i>
                                    <span class="small fw-medium">
                                        {{ __('cities.' . $linkedCity . '_title') }}
                                    </span>
                                </a>
                            </div>
                        @endif

                        {{-- Relevant Application & Visa Services Widget --}}
                        <div class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4 border-0">
                            <h4 class="widget-title h6 fw-bold mb-3 border-bottom pb-2">
                                <i class="bx bx-briefcase-alt text-primary me-2"></i>
                                {{ __('services.other_services') ?? 'خدمات اپلای و ویزا' }}
                            </h4>
                            <ul class="list-unstyled mb-0">
                                <li class="mb-2">
                                    <a href="{{ route('services.show', ['locale' => $currentLocale, 'slug' => 'university-application']) }}" class="text-decoration-none text-dark d-flex align-items-center small">
                                        <i class="bx {{ $isRtl ? 'bx-chevron-left' : 'bx-chevron-right' }} text-primary me-1"></i>
                                        <span>{{ __('services.university-application.title') ?? 'پذیرش دانشگاه و کمپوس فرانسه' }}</span>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('services.show', ['locale' => $currentLocale, 'slug' => 'student-visa']) }}" class="text-decoration-none text-dark d-flex align-items-center small">
                                        <i class="bx {{ $isRtl ? 'bx-chevron-left' : 'bx-chevron-right' }} text-primary me-1"></i>
                                        <span>{{ __('services.student-visa.title') ?? 'ویزای تحصیلی فرانسه' }}</span>
                                    </a>
                                </li>
                                <li class="mb-2">
                                    <a href="{{ route('services.show', ['locale' => $currentLocale, 'slug' => 'resume-lettre-motivation']) }}" class="text-decoration-none text-dark d-flex align-items-center small">
                                        <i class="bx {{ $isRtl ? 'bx-chevron-left' : 'bx-chevron-right' }} text-primary me-1"></i>
                                        <span>{{ __('services.resume-lettre-motivation.title') ?? 'رزومه و انگیزه‌نامه فرانسوی' }}</span>
                                    </a>
                                </li>
                                <li>
                                    <a href="{{ route('services.show', ['locale' => $currentLocale, 'slug' => 'housing-assistance']) }}" class="text-decoration-none text-dark d-flex align-items-center small">
                                        <i class="bx {{ $isRtl ? 'bx-chevron-left' : 'bx-chevron-right' }} text-primary me-1"></i>
                                        <span>{{ __('services.housing-assistance.title') ?? 'خوابگاه و کمک‌هزینه CAF' }}</span>
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
                            {!! $universityContentWithIds !!}

                            @php
                                $slug = $universitySlug;
                                $hasScholarshipLang = Lang::has("university/{$slug}.scholarships_title");
                                
                                // English Fallback
                                if ($currentLocale === 'en') {
                                    $scholarshipsTitle = $hasScholarshipLang ? __("university/{$slug}.scholarships_title") : 'Scholarships & Financial Aid (2026 Update)';
                                    $scholarshipsContent = $hasScholarshipLang ? __("university/{$slug}.scholarships_content") : 'This institution offers various scholarship opportunities, including the prestigious Eiffel Excellence Scholarship, merit-based grants, and national aids like CAF housing assistance.';
                                    $scholarshipsNote = $hasScholarshipLang ? __("university/{$slug}.scholarships_note") : '<strong>Note:</strong> Scholarship eligibility and deadlines change frequently. Please check the official university website (see Useful Links in the sidebar) or contact A.V.C consultants to help you with your application.';
                                }
                                // French Fallback
                                elseif ($currentLocale === 'fr') {
                                    $scholarshipsTitle = $hasScholarshipLang ? __("university/{$slug}.scholarships_title") : 'Bourses & Aides Financières (Mise à jour 2026)';
                                    $scholarshipsContent = $hasScholarshipLang ? __("university/{$slug}.scholarships_content") : 'Cet établissement propose plusieurs options de bourses, notamment la bourse d\'excellence Eiffel, des bourses de mérite et les aides nationales comme l\'APL/CAF.';
                                    $scholarshipsNote = $hasScholarshipLang ? __("university/{$slug}.scholarships_note") : '<strong>Note:</strong> Les critères et dates limites varient. Veuillez consulter le site officiel de l\'université (voir Liens utiles dans la barre latérale) ou contacter nos conseillers A.V.C pour vous guider.';
                                }
                                // Persian default
                                else {
                                    $scholarshipsTitle = $hasScholarshipLang ? __("university/{$slug}.scholarships_title") : '🎓 بورسیه‌ها و کمک‌هزینه‌های تحصیلی (آپدیت ۲۰۲۶)';
                                    $scholarshipsContent = $hasScholarshipLang ? __("university/{$slug}.scholarships_content") : 'این دانشگاه فرصت‌های متنوعی برای دریافت بورسیه و کمک‌هزینه ارائه می‌دهد؛ از جمله بورس تحصیلی عالی ایفل، بورس‌های شایستگی اختصاصی دانشگاه، و کمک‌هزینه مسکن دولتی CAF (تا سقف ۴۰ درصد اجاره‌بها).';
                                    $scholarshipsNote = $hasScholarshipLang ? __("university/{$slug}.scholarships_note") : '<strong>⚠️ توجه مهم:</strong> ددلاین‌ها و شرایط دریافت بورسیه برای هر رشته متفاوت است. حتماً سایت رسمی خود دانشگاه (بخش Useful Links در سایدبار) را بررسی کنید یا جهت برنامه‌ریزی و اقدام، با مشاوران A.V.C تماس بگیرید.';
                                }
                            @endphp

                            <section class="mt-5 p-4 rounded-4 bg-light border border-primary-subtle" id="university-scholarships">
                                <h3 class="h5 fw-bold text-primary mb-3">
                                    <i class="bx bxs-graduation me-2"></i>{{ $scholarshipsTitle }}
                                </h3>
                                <p class="text-muted small mb-3">{{ $scholarshipsContent }}</p>
                                <div class="alert alert-info small rounded-3 mb-0">
                                    <i class="bx bx-info-circle me-1"></i>
                                    {!! $scholarshipsNote !!}
                                </div>
                            </section>
                        </div>

                        <!-- Contact Form -->
                        <div class="ask-question mt-5 pt-5 border-top">
                            <h3 class="h4 fw-bold mb-4">@yield('ask_question_title')</h3>
                            <x-forms.university-contact pageType="university" :pageName="$universityName ?? 'unknown'" />
                        </div>
                    </article>
                </div>
            </div>
        </div>
    </section>

@endsection
