@component('mail::message')
# {{ __('emails.contact.title') }}

{{ __('emails.contact.intro_admin') }}

**{{ __('emails.labels.name') }}:** {{ $submission->name }}

**{{ __('emails.labels.email') }}:** {{ $submission->email }}

**{{ __('emails.labels.phone') }}:** {{ $submission->phone_number }}

**{{ __('emails.labels.subject') }}:** {{ $submission->subject }}

**{{ __('emails.labels.message') }}:**
{{ $submission->message }}
@endcomponent