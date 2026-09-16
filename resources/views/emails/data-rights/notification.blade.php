@component('mail::message')
# [GDPR] New Data Rights Request — {{ strtoupper($dataRequest->request_type) }}

@component('mail::table')
| Field | Value |
|:------|:------|
| **Request ID** | `{{ $dataRequest->id }}` |
| **Type** | {{ $dataRequest->request_type }} |
| **Email** | {{ $dataRequest->email }} |
| **Locale** | {{ $dataRequest->locale }} |
| **Received** | {{ $dataRequest->created_at->format('d/m/Y H:i') }} |
| **Deadline (30 days)** | {{ $dataRequest->created_at->addDays(30)->format('d/m/Y') }} |
| **IP** | {{ $dataRequest->ip_address ?? 'N/A' }} |
@endcomponent

@if($dataRequest->notes_requester)
**Requester notes:**
> {{ $dataRequest->notes_requester }}
@endif

@component('mail::button', ['url' => route('admin.data-rights.show', $dataRequest->id), 'color' => 'green'])
Review Request in Admin Panel
@endcomponent

**Response required by: {{ $dataRequest->created_at->addDays(30)->format('d M Y') }}** (GDPR Art. 12)

— A.V.C Institute GDPR Compliance System
@endcomponent
