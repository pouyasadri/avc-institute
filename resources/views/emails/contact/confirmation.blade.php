@component('mail::message')
# {{ __('emails.contact.greeting', ['name' => $submission->name]) }}

{{ __('emails.contact.intro_user') }}

{!! __('emails.contact.subject_info', ['subject' => $submission->subject]) !!}

**{{ __('emails.contact.copy_message') }}**
> {{ $submission->message }}

<br>
{{ __('emails.regards') }}<br>
{{ __('emails.team') }}

@php
    $emailLocale = $submission->locale ?? app()->getLocale();
@endphp

@component('mail::panel')
<small style="color: #6c757d;">
{{ __('emails.gdpr.notice') }}<br>
<a href="{{ route('privacy', ['locale' => $emailLocale]) }}" target="_blank">{{ __('emails.gdpr.privacy_link') }}</a> · 
<a href="{{ route('data-rights', ['locale' => $emailLocale]) }}" target="_blank">{{ __('emails.gdpr.rights_link') }}</a>
</small>
@endcomponent
@endcomponent