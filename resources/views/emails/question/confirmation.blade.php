@component('mail::message')
# {{ __('emails.question.greeting', ['name' => $submission->name]) }}

{!! __('emails.question.intro_user', ['name' => $submission->page_name]) !!}

{{ __('emails.question.success_info') }}

**{{ __('emails.question.copy_message') }}**
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