@extends('layouts.city')

@php
    $currentLocale = app()->getLocale();
    $cityName = 'marseille';
@endphp

@section('title', __('city/marseille.title'))
@section('keywords', __('city/marseille.keywords'))
@section('description', __('city/marseille.description'))

@section('header_class', 'bg-marseille-city')
@section('breadcrumb_current', __('city/marseille.breadcrumb_marseille'))
@section('page_title_heading', __('city/marseille.main_heading'))

@section('toc_title', __('city/marseille.table_of_contents'))
@section('contact_title', __('city/marseille.contact_us'))
@section('consultation_text', __('city/marseille.consultation_request'))
@section('ask_question_title', __('city/marseille.ask_question'))

@section('useful_links')
    <div class="sidebar-widget p-4 rounded-5 shadow-sm bg-white mb-4 border-0">
        <h4 class="widget-title h5 fw-bold mb-3 border-bottom pb-2">{{ __('city/marseille.useful_links') }}</h4>
        <ul class="list-unstyled mb-0">
            <li>
                <a href="https://en.wikipedia.org/wiki/Marseille" target="_blank"
                    class="d-flex align-items-center text-decoration-none">
                    <i class="bx bxl-internet-explorer me-2 fs-5 text-primary"></i>
                    <span>{{ __('city/marseille.marseille_wikipedia') }}</span>
                </a>
            </li>
        </ul>
    </div>
@endsection

@push('json')
    @php
        $pageUrl = url($currentLocale . '/cities/marseille');
        $cityId = $pageUrl . '#city';

        $webPage = new \App\Services\StructuredData\WebPageSchema(
            $pageUrl,
            __('city/marseille.main_heading'),
            __('city/marseille.description'),
            $currentLocale,
            $cityId,
            asset('assets/img/cities/marseille/marseille.webp')
        );

        $city = new \App\Services\StructuredData\CityGuideSchema(
            $cityId,
            __('city/marseille.breadcrumb_marseille'),
            __('city/marseille.intro_paragraph'),
            asset('assets/img/cities/Marseille/marseille.webp'),
            ['https://en.wikipedia.org/wiki/Marseille'],
            ['lat' => 43.2965, 'lng' => 5.3698]
        );

        $breadcrumb = \App\Services\StructuredData\BreadcrumbSchema::fromArray([
            ['name' => __('layout.home') ?? 'Home', 'url' => url($currentLocale . '/')],
            ['name' => __('cities.breadcrumb_cities'), 'url' => url($currentLocale . '/cities')],
            ['name' => __('city/marseille.breadcrumb_marseille'), 'url' => $pageUrl],
        ]);

        $faqItems = __('city/marseille.faq_items');
        if (is_array($faqItems) && !empty($faqItems)) {
            $faqSchema = (new \App\Services\StructuredData\FAQSchema())->addQuestions($faqItems);
        }
    @endphp

    <x-seo.structured-data :schema="$webPage" />
    <x-seo.structured-data :schema="$city" />
    <x-seo.structured-data :schema="$breadcrumb" />
    @if(isset($faqSchema))
        <x-seo.structured-data :schema="$faqSchema" />
    @endif
@endpush

@section('city_content')
    <section class="mb-5">
        <h2 class="h3 fw-bold mb-4">{{ __('city/marseille.intro_heading') }}</h2>
        <div class="single-services-imgs mb-4">
            <img src="{{ asset('assets/img/cities/marseille/marseille1.webp') }}" alt="{{ __('city/marseille.breadcrumb_marseille') }}"
                class="img-fluid rounded-4 shadow-sm w-100">
        </div>
        <p class="lead">{!! __('city/marseille.intro_paragraph') !!}</p>
    </section>

    @if(is_array(__('city/marseille.quick_facts')))
        <div class="card border-0 shadow-sm rounded-4 mb-5 bg-light">
            <div class="card-body p-4">
                <h3 class="h5 fw-bold text-primary mb-3 d-flex align-items-center">
                    <i class='bx bxs-check-shield me-2 fs-4'></i>
                    {{ __('city/marseille.quick_facts_title') }}
                </h3>
                <div class="row g-3">
                    @foreach (__('city/marseille.quick_facts') as $item)
                        @if(is_array($item) && isset($item['label'], $item['value']))
                            <div class="col-md-6">
                                <div class="p-3 bg-white rounded-3 shadow-xs border h-100">
                                    <div class="text-muted small mb-1 fw-semibold">{{ $item['label'] }}</div>
                                    <div class="fw-bold text-dark fs-6">{{ $item['value'] }}</div>
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    {{-- 2026 Student Living Cost Breakdown Table --}}
    @if(is_array(__('city/marseille.cost_table_rows')))
        <div class="card border-0 shadow-sm rounded-4 mb-5">
            <div class="card-body p-4">
                <h3 class="h5 fw-bold text-dark mb-3 d-flex align-items-center">
                    <i class='bx bx-calculator text-primary me-2 fs-4'></i>
                    {{ __('city/marseille.cost_table_title') }}
                </h3>
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th scope="col">{{ __('city/marseille.cost_table_headers.category') }}</th>
                                <th scope="col">{{ __('city/marseille.cost_table_headers.gross') }}</th>
                                <th scope="col" class="text-primary">{{ __('city/marseille.cost_table_headers.net_caf') }}</th>
                                <th scope="col">{{ __('city/marseille.cost_table_headers.details') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach(__('city/marseille.cost_table_rows') as $row)
                                <tr>
                                    <td class="fw-semibold">{{ $row['category'] }}</td>
                                    <td>{{ $row['gross'] }}</td>
                                    <td class="fw-bold text-primary">{{ $row['net_caf'] }}</td>
                                    <td class="small text-muted">{{ $row['details'] }}</td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    <div class="rounded-4 overflow-hidden shadow-sm mb-5">
        <iframe
            src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d11342.348602206772!2d5.3698!3d43.2965!3m2!i1024!2i768!4f13.1!3m3!1m2!1s0x478af48bd6893637%3A0x408ab2ae4ba2120!2sMarseille!5e0!3m2!1sfr!2sfr!4v1691146753003!5m2!1sfr!2sfr"
            width="100%" height="450" style="border:0;" allowfullscreen="" loading="lazy"
            referrerpolicy="no-referrer-when-downgrade"></iframe>
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('city/marseille.student_life_heading') }}</h3>
        <p>{{ __('city/marseille.student_life_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/marseille.family_life_heading') }}</h3>
        <p>{{ __('city/marseille.family_life_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/marseille.lifestyle_heading') }}</h3>
        <p>{{ __('city/marseille.lifestyle_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/marseille.history_heading') }}</h3>
        <p>{{ __('city/marseille.history_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/marseille.climate_heading') }}</h3>
        <p>{{ __('city/marseille.climate_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/marseille.study_heading') }}</h3>
        <p>{{ __('city/marseille.study_paragraph') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/marseille.universities_heading') }}</h3>
        <p>{{ __('city/marseille.universities_intro') }}</p>
        <div class="row g-3 mb-4">
            <div class="col-md-4">
                <div class="p-3 rounded-4 bg-light border border-secondary-subtle h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-primary-subtle text-primary rounded-pill small">بزرگترین دانشگاه فرانسه</span>
                        </div>
                        <h4 class="h6 fw-bold mb-2 text-primary">
                            <i class="bx bxs-graduation me-1"></i>{{ __('city/marseille.university_marseille') }}
                        </h4>
                        <p class="small text-muted mb-3">{{ __('city/marseille.university_marseille_desc') }}</p>
                    </div>
                    <a href="{{ url($currentLocale . '/universities/aix-marseille-university') }}" class="btn btn-outline-primary btn-sm rounded-pill w-100">
                        مشاهده راهنمای پذیرش ۲۰۲۶
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 rounded-4 bg-light border border-secondary-subtle h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-success-subtle text-success rounded-pill small">اعتبار سه‌گانه بین‌المللی</span>
                        </div>
                        <h4 class="h6 fw-bold mb-2 text-primary">
                            <i class="bx bx-briefcase me-1"></i>{{ __('city/marseille.university_kedge') }}
                        </h4>
                        <p class="small text-muted mb-3">{{ __('city/marseille.university_kedge_desc') }}</p>
                    </div>
                    <a href="{{ url($currentLocale . '/consult?service=student-visa') }}" class="btn btn-outline-primary btn-sm rounded-pill w-100">
                        بررسی شرایط و بورسیه
                    </a>
                </div>
            </div>
            <div class="col-md-4">
                <div class="p-3 rounded-4 bg-light border border-secondary-subtle h-100 d-flex flex-column justify-content-between">
                    <div>
                        <div class="d-flex align-items-center justify-content-between mb-2">
                            <span class="badge bg-info-subtle text-info rounded-pill small">گراند اکول مهندسی</span>
                        </div>
                        <h4 class="h6 fw-bold mb-2 text-primary">
                            <i class="bx bx-buildings me-1"></i>{{ __('city/marseille.university_centrale') }}
                        </h4>
                        <p class="small text-muted mb-3">{{ __('city/marseille.university_centrale_desc') }}</p>
                    </div>
                    <a href="{{ url($currentLocale . '/consult?service=student-visa') }}" class="btn btn-outline-primary btn-sm rounded-pill w-100">
                        مشاوره اختصاصی مهندسی
                    </a>
                </div>
            </div>
        </div>
    </section>

    <div class="mb-5">
        <img src="{{ asset('assets/img/cities/marseille/marseille.webp') }}" alt="{{ __('city/marseille.breadcrumb_marseille') }}"
            class="img-fluid rounded-4 shadow-sm w-100">
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('city/marseille.tourism_heading') }}</h3>
        <p>{{ __('city/marseille.tourism_paragraph_1') }}</p>
        @if(is_array(__('city/marseille.tourism_items')))
            <ul class="list-group list-group-flush mb-4">
                @foreach (__('city/marseille.tourism_items') as $item)
                    <li class="list-group-item bg-transparent border-0 ps-0">
                        <i class="bx bx-camera text-primary me-2"></i>
                        {{ $item }}
                    </li>
                @endforeach
            </ul>
        @endif
        <p>{{ __('city/marseille.tourism_paragraph_2') }}</p>
        <p>{{ __('city/marseille.tourism_paragraph_3') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/marseille.economy_heading') }}</h3>
        <p>{{ __('city/marseille.economy_paragraph_1') }}</p>
        @if(is_array(__('city/marseille.economy_companies')))
            <ul class="list-group list-group-flush mb-4">
                @foreach (__('city/marseille.economy_companies') as $company)
                    <li class="list-group-item bg-transparent border-0 ps-0">
                        <i class="bx bx-buildings text-primary me-2"></i>
                        {{ $company }}
                    </li>
                @endforeach
            </ul>
        @endif
        <p>{{ __('city/marseille.economy_paragraph_2') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/marseille.living_costs_heading') }}</h3>
        <p>{!! __('city/marseille.living_costs_paragraph', ['consult_url' => url($currentLocale . '/consult')]) !!}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/marseille.job_heading') }}</h3>
        <p>{{ __('city/marseille.job_paragraph_1') }}</p>
        <p>{{ __('city/marseille.job_paragraph_2') }}</p>

        <h3 class="h4 fw-bold mt-4 mb-3">{{ __('city/marseille.visa_heading') }}</h3>
        <p>{{ __('city/marseille.visa_paragraph') }}</p>
    </section>

    {{-- Iranian Community & Student Guide 2026 --}}
    <section class="mb-5" id="iranians-in-marseille">
        <h3 class="h4 fw-bold mb-3">{{ __('city/marseille.iranians_heading') }}</h3>
        <p>{{ __('city/marseille.iranians_paragraph_1') }}</p>
        <p>{{ __('city/marseille.iranians_paragraph_2') }}</p>

        @if(is_array(__('city/marseille.iranians_checklist')))
            <div class="row g-2 my-3">
                @foreach (__('city/marseille.iranians_checklist') as $item)
                    <div class="col-md-6">
                        <div class="d-flex align-items-start p-3 rounded-4 bg-light border-0 h-100">
                            <i class="bx bx-check-circle text-primary me-2 mt-1 fs-5"></i>
                            <span class="small fw-medium">{{ $item }}</span>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif

        <p class="mt-3">{{ __('city/marseille.iranians_paragraph_3') }}</p>

        <div class="mt-4">
            <a href="{{ url($currentLocale . '/consult?service=student-visa') }}"
               class="btn btn-primary rounded-pill px-4 py-2">
                <i class="bx bx-chat me-1"></i>
                {{ __('city/marseille.consultation_request') }}
            </a>
        </div>
    </section>

    {{-- Sibling University Cities Cross-linking --}}
    <section class="mb-5">
        <h3 class="h4 fw-bold mb-2">{{ __('city/marseille.sibling_cities_heading') }}</h3>
        <p class="text-muted mb-4">{{ __('city/marseille.sibling_cities_subtitle') }}</p>
        <div class="row g-3">
            <div class="col-6 col-md-4">
                <a href="{{ url($currentLocale . '/cities/montpellier') }}" class="card border-0 shadow-xs rounded-4 p-3 text-decoration-none bg-light h-100 transition-all hover-lift">
                    <div class="fw-bold text-dark mb-1 d-flex align-items-center"><i class="bx bxs-map-pin text-primary me-1"></i>مونپلیه (Montpellier)</div>
                    <span class="text-muted small">قطب پزشکی، بوم‌شناسی و بیوتکنولوژی</span>
                </a>
            </div>
            <div class="col-6 col-md-4">
                <a href="{{ url($currentLocale . '/cities/nice') }}" class="card border-0 shadow-xs rounded-4 p-3 text-decoration-none bg-light h-100 transition-all hover-lift">
                    <div class="fw-bold text-dark mb-1 d-flex align-items-center"><i class="bx bxs-map-pin text-primary me-1"></i>نیس (Nice)</div>
                    <span class="text-muted small">قطب هوش مصنوعی و فناوری سوفیا آنتی‌پولیس</span>
                </a>
            </div>
            <div class="col-6 col-md-4">
                <a href="{{ url($currentLocale . '/cities/lyon') }}" class="card border-0 shadow-xs rounded-4 p-3 text-decoration-none bg-light h-100 transition-all hover-lift">
                    <div class="fw-bold text-dark mb-1 d-flex align-items-center"><i class="bx bxs-map-pin text-primary me-1"></i>لیون (Lyon)</div>
                    <span class="text-muted small">دومین قطب دانشگاهی و صنایع های‌تک</span>
                </a>
            </div>
            <div class="col-6 col-md-4">
                <a href="{{ url($currentLocale . '/cities/toulouse') }}" class="card border-0 shadow-xs rounded-4 p-3 text-decoration-none bg-light h-100 transition-all hover-lift">
                    <div class="fw-bold text-dark mb-1 d-flex align-items-center"><i class="bx bxs-map-pin text-primary me-1"></i>تولوز (Toulouse)</div>
                    <span class="text-muted small">پایتخت هوانوردی و مهندسی هوافضا</span>
                </a>
            </div>
            <div class="col-6 col-md-4">
                <a href="{{ url($currentLocale . '/cities/paris') }}" class="card border-0 shadow-xs rounded-4 p-3 text-decoration-none bg-light h-100 transition-all hover-lift">
                    <div class="fw-bold text-dark mb-1 d-flex align-items-center"><i class="bx bxs-map-pin text-primary me-1"></i>پاریس (Paris)</div>
                    <span class="text-muted small">پایتخت علمی، تجاری و سیاسی فرانسه</span>
                </a>
            </div>
            <div class="col-6 col-md-4">
                <a href="{{ url($currentLocale . '/cities/strasbourg') }}" class="card border-0 shadow-xs rounded-4 p-3 text-decoration-none bg-light h-100 transition-all hover-lift">
                    <div class="fw-bold text-dark mb-1 d-flex align-items-center"><i class="bx bxs-map-pin text-primary me-1"></i>استراسبورگ (Strasbourg)</div>
                    <span class="text-muted small">پایتخت دیپلماسی اروپا و علوم دارویی</span>
                </a>
            </div>
        </div>
    </section>

    <div class="mb-5">
        <img src="{{ asset('assets/img/cities/marseille/marseille2.webp') }}" alt="{{ __('city/marseille.breadcrumb_marseille') }}"
            class="img-fluid rounded-4 shadow-sm w-100">
    </div>

    <section class="mb-5">
        <h3 class="h4 fw-bold mb-3">{{ __('city/marseille.conclusion_heading') }}</h3>
        <p>{!! __('city/marseille.conclusion_paragraph', ['consult_url' => url($currentLocale . '/consult')]) !!}</p>
    </section>

    <div class="car-service-list-wrap p-4 rounded-5 bg-light border-0 mt-5">
        <div class="row align-items-center">
            <div class="col-lg-4 text-center mb-4 mb-lg-0">
                <i class='bx bxs-city text-primary' style="font-size: 5rem;"></i>
                <h4 class="h5 fw-bold mt-3">{{ __('city/marseille.breadcrumb_marseille') }}</h4>
            </div>
            <div class="col-lg-8">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/marseille.student_life_heading') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/marseille.family_life_heading') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/marseille.lifestyle_heading') }}</span>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex align-items-start small text-muted">
                            <i class='bx bx-check-circle text-primary me-2 mt-1'></i>
                            <span>{{ __('city/marseille.study_heading') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="cta-consult-banner my-5 py-4 px-4 px-md-5 rounded-5 shadow-sm text-center text-md-start bg-primary text-white position-relative overflow-hidden">
        <div class="row align-items-center position-relative" style="z-index: 2;">
            <div class="col-lg-8 mb-3 mb-lg-0">
                <h3 class="h4 fw-bold text-white mb-2">{{ __('city/marseille.cta_banner_title') }}</h3>
                <p class="mb-0 text-white-50 fs-6">{{ __('city/marseille.cta_banner_subtitle') }}</p>
            </div>
            <div class="col-lg-4 text-center text-lg-end">
                <a href="{{ url($currentLocale . '/consult?service=student-visa') }}" class="btn btn-light btn-lg rounded-pill fw-bold px-4 py-3 text-primary shadow-sm">
                    <i class='bx bx-calendar-event me-2'></i>
                    {{ __('city/marseille.cta_banner_button') }}
                </a>
            </div>
        </div>
    </div>

    <div class="my-5">
        <x-sections.faq :title="__('city/marseille.faq_title')" :subtitle="__('city/marseille.faq_subtitle')"
            :items="__('city/marseille.faq_items')" id="marseille-faq" />
    </div>
@endsection
