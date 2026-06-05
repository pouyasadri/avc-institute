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
@endcomponent