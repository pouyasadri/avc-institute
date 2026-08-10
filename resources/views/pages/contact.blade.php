@extends('layouts.main')

@php
    $currentLocale = app()->getLocale();
    $isRtl = in_array($currentLocale, ['fa'], true);
    $arrowIcon = $isRtl ? 'flaticon-left-arrow' : 'flaticon-right-arrow';

    // Metadata for SEO
    $pageTitle = __('contact.meta.title');
    $pageKeywords = __('contact.meta.keywords');
    $pageDescription = __('contact.meta.description');
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
            ['url' => url($currentLocale . '/'), 'label' => __('contact.breadcrumb.home')],
            ['label' => __('contact.breadcrumb.contact')]
        ]" />
                    <h1>{{ __('contact.page_title') }}</h1>
                </div>
            </div>
        </header>
        <!-- End Page Title Area -->

        <!-- Start Contact Area -->
        <section class="main-contact-area contact-info-area contact-info-three pt-100 pb-70">
            <div class="container">
                <!-- Section Title -->
                <div class="section-title text-center mb-5">
                    <span
                        class="text-uppercase fw-bold text-primary tracking-wider">{{ __('contact.section.subtitle') }}</span>
                    <h2 class="display-6 fw-bold mt-2">{{ __('contact.section.title') }}</h2>
                    <p class="mx-auto mt-3 text-muted" style="max-width: 800px;">
                        {{ __('contact.section.description') }}
                    </p>
                </div>

                <div class="row g-4">
                    <!-- Contact Form -->
                    <div class="col-lg-7">
                        <div class="contact-wrap rounded-5 shadow-sm p-4 p-md-5 bg-white border-0 transition-all">
                            <div class="contact-form">
                                <form action="{{ route('contact.submit') }}" method="POST" id="contactForm">
                                    @csrf

                                    @if (session('success'))
                                        <div class="alert alert-success rounded-4 mb-4 border-0 shadow-sm">
                                            <i class="bx bx-check-circle me-2"></i>
                                            {{ session('success') }}
                                        </div>
                                    @endif

                                    @if (session('error'))
                                        <div class="alert alert-danger rounded-4 mb-4 border-0 shadow-sm">
                                            <i class="bx bx-error-circle me-2"></i>
                                            {{ session('error') }}
                                        </div>
                                    @endif

                                    <div class="row g-3">
                                        <div class="col-lg-6 col-sm-6">
                                            <div class="form-group mb-3">
                                                <label for="name"
                                                    class="form-label small fw-bold text-muted">{{ __('contact.form.name.label' ?? 'Name') }}</label>
                                                <input type="text" name="name" id="name"
                                                    class="form-control rounded-pill px-4 py-2" required
                                                    data-error="{{ __('contact.form.name.error') }}"
                                                    placeholder="{{ __('contact.form.name.placeholder') }}">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-sm-6">
                                            <div class="form-group mb-3">
                                                <label for="email"
                                                    class="form-label small fw-bold text-muted">{{ __('contact.form.email.label' ?? 'Email Address') }}</label>
                                                <input type="email" name="email" id="email"
                                                    class="form-control rounded-pill px-4 py-2" required
                                                    data-error="{{ __('contact.form.email.error') }}"
                                                    placeholder="{{ __('contact.form.email.placeholder') }}">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-sm-6">
                                            <div class="form-group mb-3">
                                                <label for="phone_number"
                                                    class="form-label small fw-bold text-muted">{{ __('contact.form.phone.label' ?? 'Phone Number') }}</label>
                                                <input type="text" name="phone_number" id="phone_number" required
                                                    data-error="{{ __('contact.form.phone.error') }}"
                                                    class="form-control rounded-pill px-4 py-2"
                                                    placeholder="{{ __('contact.form.phone.placeholder') }}">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>

                                        <div class="col-lg-6 col-sm-6">
                                            <div class="form-group mb-3">
                                                <label for="visa_type"
                                                    class="form-label small fw-bold text-muted">{{ __('contact.form.visa_type.label' ?? 'Visa Category') }}</label>
                                                <select name="visa_type" id="visa_type" class="form-select rounded-pill px-4 py-2">
                                                    <option value="" disabled selected>{{ __('contact.form.visa_type.placeholder' ?? 'Select Visa Type') }}</option>
                                                    @if(is_array(__('contact.form.visa_type.options')))
                                                        @foreach(__('contact.form.visa_type.options') as $key => $optionLabel)
                                                            <option value="{{ $key }}">{{ $optionLabel }}</option>
                                                        @endforeach
                                                    @endif
                                                </select>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-md-12">
                                            <div class="form-group mb-3">
                                                <label for="msg_subject"
                                                    class="form-label small fw-bold text-muted">{{ __('contact.form.subject.label' ?? 'Subject') }}</label>
                                                <input type="text" name="msg_subject" id="msg_subject"
                                                    class="form-control rounded-pill px-4 py-2" required
                                                    data-error="{{ __('contact.form.subject.error') }}"
                                                    placeholder="{{ __('contact.form.subject.placeholder') }}">
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-md-12">
                                            <div class="form-group mb-4">
                                                <label for="message"
                                                    class="form-label small fw-bold text-muted">{{ __('contact.form.message.label' ?? 'Your Message') }}</label>
                                                <textarea name="message" class="form-control rounded-4 px-4 py-3"
                                                    id="message" cols="30" rows="5" required
                                                    data-error="{{ __('contact.form.message.error') }}"
                                                    placeholder="{{ __('contact.form.message.placeholder') }}"></textarea>
                                                <div class="help-block with-errors"></div>
                                            </div>
                                        </div>

                                        <div class="col-lg-12 col-md-12">
                                            <button type="submit" class="default-btn rounded-pill px-5 transition-all">
                                                {{ __('contact.form.submit') }}
                                                <i class="{{ $arrowIcon }}"></i>
                                            </button>
                                            <div id="msgSubmit" class="h3 text-center hidden"></div>
                                            <div class="clearfix"></div>
                                        </div>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>

                    <!-- Contact Information -->
                    <div class="col-lg-5">
                        <div class="row g-4">
                            <!-- Email Contact Info -->
                            <div class="col-12">
                                <div
                                    class="single-contact-info rounded-5 shadow-sm p-4 bg-white border-0 transition-all hover-lift h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="flex-shrink-0 bg-primary-subtle text-primary rounded-pill p-3 me-3">
                                            <i class="bx bx-envelope fs-3"></i>
                                        </div>
                                        <h3 class="h5 fw-bold mb-0">{{ __('contact.info.email.title') }}</h3>
                                    </div>
                                    <a href="mailto:{{ __('contact.info.email.value') }}"
                                        class="text-muted text-decoration-none">
                                        {{ __('contact.info.email.value') }}
                                    </a>
                                </div>
                            </div>

                            <!-- Phone Contact Info -->
                            <div class="col-12">
                                <div
                                    class="single-contact-info rounded-5 shadow-sm p-4 bg-white border-0 transition-all hover-lift h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="flex-shrink-0 bg-success-subtle text-success rounded-pill p-3 me-3">
                                            <i class="bx bx-phone-call fs-3"></i>
                                        </div>
                                        <h3 class="h5 fw-bold mb-0">{{ __('contact.info.phone.title') }}</h3>
                                    </div>
                                    <div class="d-flex flex-column gap-2">
                                        <a href="tel:+989120087194" class="text-muted text-decoration-none">
                                            <span
                                                class="badge bg-light text-dark me-2">{{ __('contact.info.phone.iran_label' ?? 'Iran') }}</span>
                                            <span dir="ltr">{{ __('contact.info.phone.iran') }}</span>
                                        </a>
                                        <a href="tel:+33768688326" class="text-muted text-decoration-none">
                                            <span
                                                class="badge bg-light text-dark me-2">{{ __('contact.info.phone.france_label' ?? 'France') }}</span>
                                            <span dir="ltr">{{ __('contact.info.phone.france') }}</span>
                                        </a>
                                    </div>
                                </div>
                            </div>

                            <!-- Hours Info (Optional/New) -->
                            <div class="col-12">
                                <div
                                    class="single-contact-info rounded-5 shadow-sm p-4 bg-white border-0 transition-all hover-lift h-100">
                                    <div class="d-flex align-items-center mb-3">
                                        <div class="flex-shrink-0 bg-info-subtle text-info rounded-pill p-3 me-3">
                                            <i class="bx bx-time fs-3"></i>
                                        </div>
                                        <h3 class="h5 fw-bold mb-0">{{ __('contact.info.hours.title' ?? 'Working Hours') }}
                                        </h3>
                                    </div>
                                    <p class="text-muted mb-0 small">
                                        {{ __('contact.info.hours.description' ?? 'Mon - Fri: 9:00 AM - 6:00 PM') }}
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        <!-- End Contact Area -->
    </div>
@endsection

@push('json')
    @php
        $breadcrumb = \App\Services\StructuredData\BreadcrumbSchema::fromArray([
            ['name' => __('contact.breadcrumb.home'), 'url' => url($currentLocale . '/')],
            ['name' => __('contact.breadcrumb.contact'), 'url' => url($currentLocale . '/contactUs')],
        ]);

        $contactPageArray = [
            '@context' => 'https://schema.org',
            '@type' => 'ContactPage',
            'url' => url()->current(),
            'name' => $pageTitle,
            'description' => $pageDescription,
            'inLanguage' => $currentLocale,
            'mainEntity' => [
                '@type' => 'ProfessionalService',
                '@id' => rtrim(config('app.url'), '/') . '/#organization',
                'name' => 'A.V.C Institute',
                'image' => asset('assets/images/logo.png'),
                'priceRange' => '$$',
                'telephone' => '+33-7-68-68-83-26',
                'email' => 'info@applyvipconseil.com',
                'areaServed' => ['IR', 'FR'],
                'knowsAbout' => [
                    'France Immigration',
                    'Campus France Student Visa',
                    'Visiteur Financial Independence Visa',
                    'France Residence Permits 2026',
                    'Real Estate Investment France'
                ],
                'contactPoint' => [
                    [
                        '@type' => 'ContactPoint',
                        'telephone' => '+98-912-008-7194',
                        'contactType' => 'customer service',
                        'areaServed' => 'IR',
                        'availableLanguage' => ['Persian', 'English'],
                    ],
                    [
                        '@type' => 'ContactPoint',
                        'telephone' => '+33-7-68-68-83-26',
                        'contactType' => 'customer service',
                        'areaServed' => 'FR',
                        'availableLanguage' => ['French', 'English', 'Persian'],
                    ],
                ],
            ],
        ];
    @endphp

    <x-seo.structured-data :schema="$contactPageArray" />
    <x-seo.structured-data :schema="$breadcrumb" />
@endpush