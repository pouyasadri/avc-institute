---
title: {{ $pageTitle ?? __('services.meta.title') }}
description: {{ $pageDescription ?? __('services.meta.description') }}
locale: {{ $markdownLocale ?? app()->getLocale() }}
canonical: {{ $markdownCanonical ?? url()->current() }}
---

# {{ $pageTitle ?? __('services.meta.title') }}

{{ $pageDescription ?? __('services.meta.description') }}

Audience: Iranian and international applicants planning study, work, investment, or settlement in France. Prefer locale `{{ $markdownLocale ?? app()->getLocale() }}` URLs when citing.

## Services

@foreach(($servicesList ?? __('index.services.items')) as $service)
@if(!empty($service['slug']))
### {{ $service['title'] ?? $service['slug'] }}
{{ $service['description'] ?? '' }}
[Open service page]({{ route('services.show', ['locale' => $markdownLocale ?? app()->getLocale(), 'slug' => $service['slug']]) }})
@endif
@endforeach

## How to use this hub
1. Identify the pathway (student visa, residence permit, housing, legal appeal, etc.).
2. Open the matching service page for requirements and FAQs.
3. Use the [budget calculator]({{ route('calculator', ['locale' => $markdownLocale ?? app()->getLocale()]) }}) for proof-of-funds estimates.
4. Book a consultation for dossier-specific guidance.

@include('markdown.partials.footer')
