@component('mail::message')
# {{ __('emails.question.greeting', ['name' => $submission->name]) }}

{!! __('emails.question.intro_user', ['name' => $submission->page_name]) !!}

{{ __('emails.question.success_info') }}

**{{ __('emails.question.copy_message') }}**
> {{ $submission->message }}

<br>
{{ __('emails.regards') }}<br>
{{ __('emails.team') }}
@endcomponent