@component('mail::message')
# {{ __('emails.contact.greeting', ['name' => $submission->name]) }}

{{ __('emails.contact.intro_user') }}

{!! __('emails.contact.subject_info', ['subject' => $submission->subject]) !!}

**{{ __('emails.contact.copy_message') }}**
> {{ $submission->message }}

<br>
{{ __('emails.regards') }}<br>
{{ __('emails.team') }}
@endcomponent