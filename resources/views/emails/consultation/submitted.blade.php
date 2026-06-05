@component('mail::message')
# {{ __('emails.consultation.title') }}

{{ __('emails.consultation.intro_admin') }}

**{{ __('emails.labels.name') }}:** {{ $data['user_name'] }}

**{{ __('emails.labels.email') }}:** {{ $data['user_email'] }}

**{{ __('emails.labels.phone') }}:** {{ $data['user_phone_number'] }}

**{{ __('emails.labels.service') }}:** {{ $data['user_service'] }}

**{{ __('emails.labels.details') }}:**
{{ $data['user_details'] }}
@endcomponent