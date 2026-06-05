@component('mail::message')
# {{ __('emails.question.title') }}

{{ __('emails.question.intro_admin') }}

**{{ __('emails.labels.name') }}:** {{ $submission->name }}

**{{ __('emails.labels.email') }}:** {{ $submission->email }}

**{{ __('emails.labels.phone') }}:** {{ $submission->phone_number }}

**{{ __('emails.labels.subject') }}:** {{ $submission->subject }}

**{{ __('emails.labels.page_type') }}:** {{ $submission->page_type }}

**{{ __('emails.labels.page_name') }}:** {{ $submission->page_name }}

**{{ __('emails.labels.message') }}:**
{{ $submission->message }}
@endcomponent