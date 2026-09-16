@extends('layouts.main')

@section('title', __('privacy.data_rights.meta.title'))

@section('content')
    {{-- Hero --}}
    <section style="background:linear-gradient(135deg,#1a1a2e 0%,#16213e 60%,#0f3460 100%);padding:70px 0 50px;color:#fff;">
        <div class="container text-center">
            <span style="display:inline-flex;align-items:center;gap:6px;background:rgba(40,167,69,.2);border:1px solid rgba(40,167,69,.5);color:#5cb85c;border-radius:50px;padding:6px 16px;font-size:.8rem;font-weight:600;text-transform:uppercase;margin-bottom:1rem;">
                <i class="bx bx-shield-quarter"></i> GDPR · Art. 15–22
            </span>
            <h1 style="font-size:2rem;font-weight:700;">{{ __('privacy.data_rights.title') }}</h1>
            <p class="text-white-50 mb-0 mx-auto" style="max-width:600px;">{{ __('privacy.data_rights.subtitle') }}</p>
        </div>
    </section>

    <section class="section-padding">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">

                    @if(session('success'))
                        <div class="alert alert-success border-0 rounded-4 d-flex align-items-center gap-2 mb-4">
                            <i class="bx bx-check-circle fs-4"></i>
                            <div>{{ session('success') }}</div>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger border-0 rounded-4 mb-4">{{ session('error') }}</div>
                    @endif

                    {{-- Rights overview cards --}}
                    <div class="row g-3 mb-4">
                        @foreach([
                            ['type'=>'access',        'icon'=>'bx-search-alt', 'color'=>'primary'],
                            ['type'=>'rectification', 'icon'=>'bx-edit',       'color'=>'info'],
                            ['type'=>'erasure',       'icon'=>'bx-trash-alt',  'color'=>'danger'],
                            ['type'=>'portability',   'icon'=>'bx-export',     'color'=>'success'],
                            ['type'=>'objection',     'icon'=>'bx-block',      'color'=>'warning'],
                            ['type'=>'restriction',   'icon'=>'bx-pause',      'color'=>'secondary'],
                        ] as $r)
                        <div class="col-md-4 col-6">
                            <div class="border rounded-4 p-3 h-100 text-center bg-light">
                                <div class="mb-1"><i class="bx {{ $r['icon'] }} text-{{ $r['color'] }} fs-3"></i></div>
                                <div class="small fw-semibold text-dark">{{ __('privacy.data_rights.types.' . $r['type'] . '.label') }}</div>
                                <div class="small text-muted" style="font-size:.75rem;">{{ __('privacy.data_rights.types.' . $r['type'] . '.article') }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>

                    {{-- Form --}}
                    <div class="card border-0 shadow-sm rounded-4 overflow-hidden">
                        <div class="card-header bg-dark text-white py-3 px-4">
                            <h5 class="mb-0"><i class="bx bx-send me-2"></i>{{ __('privacy.data_rights.form.heading') }}</h5>
                        </div>
                        <div class="card-body p-4">
                            <form action="{{ route('data-rights.submit', ['locale' => app()->getLocale()]) }}" method="POST">
                                @csrf

                                {{-- Email --}}
                                <div class="mb-3">
                                    <label for="dr_email" class="form-label fw-semibold">{{ __('privacy.data_rights.form.email') }} <span class="text-danger">*</span></label>
                                    <input type="email" name="email" id="dr_email"
                                        class="form-control rounded-3 @error('email') is-invalid @enderror"
                                        value="{{ old('email') }}" required
                                        placeholder="your@email.com">
                                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    <div class="form-text">{{ __('privacy.data_rights.form.email_hint') }}</div>
                                </div>

                                {{-- Request type --}}
                                <div class="mb-3">
                                    <label class="form-label fw-semibold">{{ __('privacy.data_rights.form.request_type') }} <span class="text-danger">*</span></label>
                                    <div class="@error('request_type') is-invalid @enderror">
                                        @foreach(['access','rectification','erasure','portability','objection','restriction'] as $type)
                                        <div class="form-check border rounded-3 p-3 mb-2 {{ old('request_type') === $type ? 'border-success bg-light' : '' }}">
                                            <input class="form-check-input" type="radio" name="request_type"
                                                id="rt_{{ $type }}" value="{{ $type }}"
                                                {{ old('request_type') === $type ? 'checked' : '' }} required>
                                            <label class="form-check-label w-100" for="rt_{{ $type }}">
                                                <span class="fw-semibold text-dark">{{ __('privacy.data_rights.types.' . $type . '.label') }}</span>
                                                <span class="text-muted small d-block">{{ __('privacy.data_rights.types.' . $type . '.desc') }}</span>
                                            </label>
                                        </div>
                                        @endforeach
                                    </div>
                                    @error('request_type')<div class="invalid-feedback d-block">{{ $message }}</div>@enderror
                                </div>

                                {{-- Optional notes --}}
                                <div class="mb-3">
                                    <label for="dr_notes" class="form-label fw-semibold">{{ __('privacy.data_rights.form.notes') }}</label>
                                    <textarea name="notes_requester" id="dr_notes" rows="3"
                                        class="form-control rounded-3 @error('notes_requester') is-invalid @enderror"
                                        placeholder="{{ __('privacy.data_rights.form.notes_placeholder') }}">{{ old('notes_requester') }}</textarea>
                                    @error('notes_requester')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                </div>

                                {{-- Consent checkbox --}}
                                <div class="mb-4">
                                    <div class="form-check">
                                        <input class="form-check-input @error('gdpr_consent') is-invalid @enderror"
                                            type="checkbox" name="gdpr_consent" id="dr_consent" value="1"
                                            required {{ old('gdpr_consent') ? 'checked' : '' }}>
                                        <label class="form-check-label small text-muted" for="dr_consent">
                                            {!! __('privacy.form.gdpr_consent_label', ['url' => route('privacy', ['locale' => app()->getLocale()])]) !!}
                                        </label>
                                        @error('gdpr_consent')<div class="invalid-feedback">{{ $message }}</div>@enderror
                                    </div>
                                </div>

                                <button type="submit" class="default-btn rounded-pill px-5 transition-all">
                                    <i class="bx bx-send me-1"></i>
                                    {{ __('privacy.data_rights.form.submit') }}
                                </button>
                            </form>
                        </div>
                    </div>

                    {{-- Response time notice --}}
                    <div class="alert border-0 rounded-4 mt-4 py-3 px-4" style="background:#f0fdf4;border-left:4px solid #28a745 !important;">
                        <p class="mb-1 fw-semibold"><i class="bx bx-time me-1 text-success"></i> {{ __('privacy.data_rights.response_time_heading') }}</p>
                        <p class="mb-0 small text-muted">{!! __('privacy.data_rights.response_time_body') !!}</p>
                    </div>

                </div>
            </div>
        </div>
    </section>
@endsection
