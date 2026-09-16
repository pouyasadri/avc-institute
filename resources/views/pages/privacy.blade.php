@extends('layouts.main')

@section('title', __('privacy.meta.title'))

@push('styles')
<style>
    .privacy-hero {
        background: linear-gradient(135deg, #1a1a2e 0%, #16213e 60%, #0f3460 100%);
        padding: 80px 0 60px;
        color: #fff;
    }
    .privacy-hero .badge-gdpr {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(40,167,69,0.2);
        border: 1px solid rgba(40,167,69,0.5);
        color: #5cb85c;
        border-radius: 50px;
        padding: 6px 16px;
        font-size: 0.8rem;
        font-weight: 600;
        letter-spacing: 0.5px;
        text-transform: uppercase;
        margin-bottom: 1rem;
    }
    .privacy-hero h1 { font-size: 2.2rem; font-weight: 700; margin-bottom: 0.5rem; }
    .privacy-hero .version-pill {
        display: inline-flex;
        align-items: center;
        gap: 6px;
        background: rgba(255,255,255,0.1);
        border-radius: 50px;
        padding: 4px 14px;
        font-size: 0.78rem;
        color: rgba(255,255,255,0.7);
        margin-top: 0.5rem;
    }
    .privacy-toc {
        background: #f8fafc;
        border-left: 4px solid #28a745;
        border-radius: 0 12px 12px 0;
        padding: 20px 24px;
        margin-bottom: 2rem;
    }
    [dir="rtl"] .privacy-toc { border-left: none; border-right: 4px solid #28a745; border-radius: 12px 0 0 12px; }
    .privacy-toc h2 { font-size: 1rem; font-weight: 700; margin-bottom: 0.75rem; color: #1a1a2e; }
    .privacy-toc ol { margin: 0; padding-{{ app()->getLocale() === 'fa' ? 'right' : 'left' }}: 1.2rem; }
    .privacy-toc li { margin-bottom: 4px; }
    .privacy-toc a { color: #28a745; text-decoration: none; font-size: 0.9rem; }
    .privacy-toc a:hover { text-decoration: underline; }

    .privacy-section { scroll-margin-top: 90px; margin-bottom: 2.5rem; }
    .privacy-section h2 {
        font-size: 1.3rem;
        font-weight: 700;
        color: #1a1a2e;
        border-bottom: 2px solid #e9ecef;
        padding-bottom: 0.5rem;
        margin-bottom: 1rem;
    }
    .privacy-section p, .privacy-section li { color: #4a5568; line-height: 1.75; }
    .privacy-section a { color: #28a745; }

    .gdpr-table { width: 100%; border-collapse: collapse; font-size: 0.88rem; }
    .gdpr-table th { background: #1a1a2e; color: #fff; padding: 10px 14px; text-align: {{ app()->getLocale() === 'fa' ? 'right' : 'left' }}; }
    .gdpr-table td { padding: 9px 14px; border-bottom: 1px solid #e9ecef; vertical-align: top; color: #4a5568; }
    .gdpr-table tr:nth-child(even) td { background: #f8fafc; }

    .right-card {
        background: #fff;
        border: 1px solid #e9ecef;
        border-radius: 12px;
        padding: 14px 18px;
        margin-bottom: 10px;
        display: flex;
        gap: 12px;
        align-items: flex-start;
    }
    .right-card .right-icon {
        width: 32px;
        height: 32px;
        background: rgba(40,167,69,0.12);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        flex-shrink: 0;
        color: #28a745;
        font-size: 1rem;
    }
    .right-card strong { display: block; font-size: 0.88rem; color: #1a1a2e; }
    .right-card span { font-size: 0.83rem; color: #6c757d; }

    .contact-box {
        background: linear-gradient(135deg, #f0fdf4 0%, #dcfce7 100%);
        border: 1px solid #86efac;
        border-radius: 16px;
        padding: 24px;
    }
    .contact-box a { color: #16a34a; }
</style>
@endpush

@section('content')
    {{-- Hero --}}
    <section class="privacy-hero">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8 text-center">
                    <span class="badge-gdpr">
                        <i class="bx bx-shield-quarter"></i>
                        GDPR · EU 2016/679
                    </span>
                    <h1>{{ __('privacy.title') }}</h1>
                    <p class="text-white-50 mb-0">{{ __('privacy.intro') }}</p>
                    <div class="version-pill">
                        <i class="bx bx-history"></i>
                        {{ __('privacy.version') }} &nbsp;·&nbsp; {{ __('privacy.updated') }}
                    </div>
                </div>
            </div>
        </div>
    </section>

    {{-- Body --}}
    <section class="section-padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-9">

                    {{-- Table of contents --}}
                    <div class="privacy-toc">
                        <h2><i class="bx bx-list-ul me-1"></i> {{ app()->getLocale() === 'fa' ? 'فهرست مطالب' : (app()->getLocale() === 'fr' ? 'Table des matières' : 'Table of Contents') }}</h2>
                        <ol>
                            <li><a href="#section-controller">{{ __('privacy.controller.heading') }}</a></li>
                            <li><a href="#section-data">{{ __('privacy.data_collected.heading') }}</a></li>
                            <li><a href="#section-basis">{{ __('privacy.legal_basis.heading') }}</a></li>
                            <li><a href="#section-purposes">{{ __('privacy.purposes.heading') }}</a></li>
                            <li><a href="#section-cookies">{{ __('privacy.cookies.heading') }}</a></li>
                            <li><a href="#section-sharing">{{ __('privacy.sharing.heading') }}</a></li>
                            <li><a href="#section-retention">{{ __('privacy.retention.heading') }}</a></li>
                            <li><a href="#section-rights">{{ __('privacy.rights.heading') }}</a></li>
                            <li><a href="#section-security">{{ __('privacy.security.heading') }}</a></li>
                            <li><a href="#section-transfers">{{ __('privacy.transfers.heading') }}</a></li>
                            <li><a href="#section-children">{{ __('privacy.children.heading') }}</a></li>
                            <li><a href="#section-updates">{{ __('privacy.updates.heading') }}</a></li>
                            <li><a href="#section-contact">{{ __('privacy.contact_us.heading') }}</a></li>
                        </ol>
                    </div>

                    {{-- § 1 Controller --}}
                    <div id="section-controller" class="privacy-section">
                        <h2>{{ __('privacy.controller.heading') }}</h2>
                        <p>{{ __('privacy.controller.body') }}</p>
                        <ul>
                            <li><strong>{{ __('privacy.controller.name') }}</strong></li>
                            <li><i class="bx bx-map-pin me-1 text-success"></i> {{ __('privacy.controller.address') }}</li>
                            <li><i class="bx bx-envelope me-1 text-success"></i> <a href="mailto:{{ __('privacy.controller.email') }}">{{ __('privacy.controller.email') }}</a></li>
                            <li><i class="bx bx-phone me-1 text-success"></i> <a href="tel:+33768688326" dir="ltr">{{ __('privacy.controller.phone') }}</a></li>
                        </ul>
                        <p>{!! __('privacy.controller.note') !!}</p>
                    </div>

                    {{-- § 2 Data collected --}}
                    <div id="section-data" class="privacy-section">
                        <h2>{{ __('privacy.data_collected.heading') }}</h2>
                        <p>{{ __('privacy.data_collected.intro') }}</p>
                        @foreach(__('privacy.data_collected.categories') as $cat)
                            <h6 class="fw-bold mt-3 mb-1 text-dark">{{ $cat['name'] }}</h6>
                            <ul>
                                @foreach($cat['items'] as $item)
                                    <li>{!! $item !!}</li>
                                @endforeach
                            </ul>
                        @endforeach
                        <div class="alert alert-info border-0 rounded-3 mt-3 py-2 px-3 small">
                            <i class="bx bx-info-circle me-1"></i>
                            {!! __('privacy.data_collected.not_collected') !!}
                        </div>
                    </div>

                    {{-- § 3 Legal basis --}}
                    <div id="section-basis" class="privacy-section">
                        <h2>{{ __('privacy.legal_basis.heading') }}</h2>
                        <p>{{ __('privacy.legal_basis.intro') }}</p>
                        <div class="table-responsive rounded-3 overflow-hidden border">
                            <table class="gdpr-table">
                                <thead>
                                    <tr>
                                        <th style="width:40%">{{ app()->getLocale() === 'fa' ? 'مبنای قانونی' : (app()->getLocale() === 'fr' ? 'Base légale' : 'Legal basis') }}</th>
                                        <th>{{ app()->getLocale() === 'fa' ? 'کاربرد' : (app()->getLocale() === 'fr' ? 'Utilisation' : 'Use case') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(__('privacy.legal_basis.bases') as $base)
                                        <tr>
                                            <td class="fw-semibold text-dark">{{ $base['basis'] }}</td>
                                            <td>{{ $base['use'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- § 4 Purposes --}}
                    <div id="section-purposes" class="privacy-section">
                        <h2>{{ __('privacy.purposes.heading') }}</h2>
                        <ul>
                            @foreach(__('privacy.purposes.items') as $item)
                                <li>{{ $item }}</li>
                            @endforeach
                        </ul>
                    </div>

                    {{-- § 5 Cookies --}}
                    <div id="section-cookies" class="privacy-section">
                        <h2>{{ __('privacy.cookies.heading') }}</h2>
                        <p>{{ __('privacy.cookies.intro') }}</p>
                        <div class="table-responsive rounded-3 overflow-hidden border">
                            <table class="gdpr-table">
                                <thead>
                                    <tr>
                                        @foreach(__('privacy.cookies.table_headers') as $h)
                                            <th>{{ $h }}</th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(__('privacy.cookies.items') as $cookie)
                                        <tr>
                                            <td><code>{{ $cookie['name'] }}</code></td>
                                            <td>{!! $cookie['purpose'] !!}</td>
                                            <td>{{ $cookie['expiry'] }}</td>
                                            <td class="small">{!! $cookie['type'] !!}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                        <p class="small text-muted mt-2"><i class="bx bx-info-circle me-1"></i> {!! __('privacy.cookies.change_mind') !!}</p>
                    </div>

                    {{-- § 6 Sharing --}}
                    <div id="section-sharing" class="privacy-section">
                        <h2>{{ __('privacy.sharing.heading') }}</h2>
                        <p>{{ __('privacy.sharing.intro') }}</p>
                        <div class="table-responsive rounded-3 overflow-hidden border">
                            <table class="gdpr-table">
                                <thead>
                                    <tr>
                                        <th>{{ app()->getLocale() === 'fa' ? 'گیرنده' : (app()->getLocale() === 'fr' ? 'Destinataire' : 'Recipient') }}</th>
                                        <th>{{ app()->getLocale() === 'fa' ? 'هدف' : (app()->getLocale() === 'fr' ? 'Finalité' : 'Purpose') }}</th>
                                        <th>{{ app()->getLocale() === 'fa' ? 'کشور' : (app()->getLocale() === 'fr' ? 'Pays' : 'Country') }}</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach(__('privacy.sharing.recipients') as $r)
                                        <tr>
                                            <td class="fw-semibold text-dark">{{ $r['name'] }}</td>
                                            <td>{!! $r['purpose'] !!}</td>
                                            <td>{{ $r['country'] }}</td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    {{-- § 7 Retention --}}
                    <div id="section-retention" class="privacy-section">
                        <h2>{{ __('privacy.retention.heading') }}</h2>
                        <p>{!! __('privacy.retention.body') !!}</p>
                    </div>

                    {{-- § 8 Rights --}}
                    <div id="section-rights" class="privacy-section">
                        <h2>{{ __('privacy.rights.heading') }}</h2>
                        <p>{{ __('privacy.rights.intro') }}</p>
                        <div class="row g-2 mb-3">
                            @foreach(__('privacy.rights.items') as $right)
                                <div class="col-md-6">
                                    <div class="right-card">
                                        <div class="right-icon"><i class="bx bx-check-shield"></i></div>
                                        <div>
                                            <strong>{{ $right['right'] }}</strong>
                                            <span>{{ $right['desc'] }}</span>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                        <p>{!! __('privacy.rights.exercise') !!}</p>
                        <div class="alert alert-warning border-0 rounded-3 py-2 px-3 small">
                            <i class="bx bx-building me-1"></i>
                            {!! __('privacy.rights.supervisory') !!}
                        </div>
                    </div>

                    {{-- § 9 Security --}}
                    <div id="section-security" class="privacy-section">
                        <h2>{{ __('privacy.security.heading') }}</h2>
                        <p>{{ __('privacy.security.body') }}</p>
                    </div>

                    {{-- § 10 Transfers --}}
                    <div id="section-transfers" class="privacy-section">
                        <h2>{{ __('privacy.transfers.heading') }}</h2>
                        <p>{{ __('privacy.transfers.body') }}</p>
                    </div>

                    {{-- § 11 Children --}}
                    <div id="section-children" class="privacy-section">
                        <h2>{{ __('privacy.children.heading') }}</h2>
                        <p>{{ __('privacy.children.body') }}</p>
                    </div>

                    {{-- § 12 Updates --}}
                    <div id="section-updates" class="privacy-section">
                        <h2>{{ __('privacy.updates.heading') }}</h2>
                        <p>{{ __('privacy.updates.body') }}</p>
                    </div>

                    {{-- § 13 Contact --}}
                    <div id="section-contact" class="privacy-section">
                        <h2>{{ __('privacy.contact_us.heading') }}</h2>
                        <p>{{ __('privacy.contact_us.body') }}</p>
                        <div class="contact-box">
                            <p class="mb-1"><i class="bx bx-buildings me-2 text-success"></i> <strong>{{ __('privacy.controller.name') }}</strong></p>
                            <p class="mb-1"><i class="bx bx-map me-2 text-success"></i> {{ __('privacy.controller.address') }}</p>
                            <p class="mb-1"><i class="bx bx-envelope me-2 text-success"></i> <a href="mailto:{{ __('privacy.controller.email') }}">{{ __('privacy.controller.email') }}</a></p>
                            <p class="mb-1"><i class="bx bx-envelope me-2 text-success"></i> {{ app()->getLocale() === 'fa' ? 'DPO:' : 'DPO:' }} <a href="mailto:dpo@applyvipconseil.com">dpo@applyvipconseil.com</a></p>
                            <p class="mb-0"><i class="bx bx-phone me-2 text-success"></i> <a href="tel:+33768688326" dir="ltr">+33 7 68 68 83 26</a></p>
                        </div>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
