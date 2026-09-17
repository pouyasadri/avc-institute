@component('mail::message')
# {{ __('emails.consultation.greeting', ['name' => $data['user_name']]) }}

{{ __('emails.consultation.intro_user') }}

{!! __('emails.consultation.service_info', ['service' => $data['user_service']]) !!}

**{{ __('emails.consultation.copy_details') }}**

**{{ __('emails.labels.phone') }}:** {{ $data['user_phone_number'] }}

**{{ __('emails.labels.details') }}:**
{{ $data['user_details'] }}

<br>
{{ __('emails.regards') }}<br>
{{ __('emails.team') }}

@php
    $emailLocale = $data['locale'] ?? app()->getLocale();
@endphp

@component('mail::panel')
<small style="color: #6c757d;">
{{ __('emails.gdpr.notice') }}<br>
<a href="{{ route('privacy', ['locale' => $emailLocale]) }}" target="_blank">{{ __('emails.gdpr.privacy_link') }}</a> · 
<a href="{{ route('data-rights', ['locale' => $emailLocale]) }}" target="_blank">{{ __('emails.gdpr.rights_link') }}</a>
</small>
@endcomponent
@endcomponent