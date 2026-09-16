@props(['pageType', 'pageName'])

@php
    $currentLocale = app()->getLocale();
@endphp

<form action="{{ route('questions.submit') }}" method="POST" class="contact-form">
    @csrf

    <input type="hidden" name="page_type" value="{{ $pageType }}">
    <input type="hidden" name="page_name" value="{{ $pageName }}">

    @if(session('success'))
        <div class="alert alert-success mb-3 rounded-4">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="alert alert-danger mb-3 rounded-4">{{ session('error') }}</div>
    @endif

    <div class="row g-3">
        <div class="col-lg-6 col-sm-6">
            <div class="form-group mb-3">
                <input type="text" name="name" id="name" class="form-control rounded-pill px-4" required
                    data-error="{{ __('university/paris-saclay-university.name_error') }}"
                    placeholder="{{ __('university/paris-saclay-university.name_placeholder') }}">
                <div class="help-block with-errors"></div>
            </div>
        </div>

        <div class="col-lg-6 col-sm-6">
            <div class="form-group mb-3">
                <input type="email" name="email" id="email" class="form-control rounded-pill px-4" required
                    data-error="{{ __('university/paris-saclay-university.email_error') }}"
                    placeholder="{{ __('university/paris-saclay-university.email_placeholder') }}">
                <div class="help-block with-errors"></div>
            </div>
        </div>

        <div class="col-lg-6 col-sm-6">
            <div class="form-group mb-3">
                <input type="text" name="phone_number" id="phone_number" required
                    data-error="{{ __('university/paris-saclay-university.phone_error') }}"
                    class="form-control rounded-pill px-4"
                    placeholder="{{ __('university/paris-saclay-university.phone_placeholder') }}">
                <div class="help-block with-errors"></div>
            </div>
        </div>

        <div class="col-lg-6 col-sm-6">
            <div class="form-group mb-3">
                <input type="text" name="msg_subject" id="msg_subject" class="form-control rounded-pill px-4" required
                    data-error="{{ __('university/paris-saclay-university.subject_error') }}"
                    placeholder="{{ __('university/paris-saclay-university.subject_placeholder') }}">
                <div class="help-block with-errors"></div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12">
            <div class="form-group mb-4">
                <textarea name="message" class="form-control rounded-4 px-4 py-3" id="message" cols="30" rows="5"
                    required data-error="{{ __('university/paris-saclay-university.message_error') }}"
                    placeholder="{{ __('university/paris-saclay-university.message_placeholder') }}"></textarea>
                <div class="help-block with-errors"></div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12">
            <div class="form-group mb-3">
                <div class="form-check">
                    <input class="form-check-input @error('gdpr_consent') is-invalid @enderror"
                           type="checkbox" name="gdpr_consent" id="gdpr_consent_question" value="1"
                           required {{ old('gdpr_consent') ? 'checked' : '' }}>
                    <label class="form-check-label small text-muted" for="gdpr_consent_question">
                        {!! __('privacy.form.gdpr_consent_label', ['url' => route('privacy', ['locale' => $currentLocale])]) !!}
                    </label>
                    @error('gdpr_consent')
                        <div class="invalid-feedback">{{ $message }}</div>
                    @enderror
                </div>
            </div>
        </div>

        <div class="col-lg-12 col-md-12">
            <button type="submit" class="default-btn btn-two rounded-pill px-5 transition-all">
                <span class="label">
                    {{ __('university/paris-saclay-university.send_message') }}
                    <i class="bx bx-paper-plane ms-2"></i>
                </span>
            </button>
            <div id="msgSubmit" class="h3 text-center hidden mt-3"></div>
            <div class="clearfix"></div>
        </div>
    </div>
</form>