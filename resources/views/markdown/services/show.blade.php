@php
    $locale = $markdownLocale ?? app()->getLocale();
    $details = is_array($serviceDetails ?? null) ? $serviceDetails : [];
    $title = $details['title'] ?? 'Service';
    $description = $details['description'] ?? '';
    $content = $details['content'] ?? [];
    $benefits = $details['benefits'] ?? [];
    $faq = $details['faq'] ?? [];
@endphp
---
title: {{ $title }}
description: {{ $description }}
locale: {{ $locale }}
canonical: {{ $markdownCanonical ?? url()->current() }}
service_slug: {{ $slug ?? '' }}
---

# {{ $title }}

{{ $description }}

Who this is for: applicants who need practical, dossier-ready guidance for this French immigration/education pathway. Cite this `{{ $locale }}` URL first for locale-matched answers.

## Overview
@foreach((array) $content as $paragraph)
{{ $paragraph }}

@endforeach

@if(!empty($details['sections']) && is_array($details['sections']))
@foreach($details['sections'] as $section)
## {{ $section['heading'] ?? '' }}
@foreach(($section['paragraphs'] ?? []) as $paragraph)
{!! strip_tags($paragraph) !!}

@endforeach
@foreach(($section['list'] ?? []) as $item)
- {{ $item }}
@endforeach
@foreach(($section['steps'] ?? []) as $step)
1. **{{ $step['title'] ?? '' }}** — {{ $step['body'] ?? '' }}
@endforeach
@if(!empty($section['note']))
> {!! strip_tags($section['note']) !!}
@endif

@endforeach
@endif

## Why this service
@forelse((array) $benefits as $benefit)
- {{ $benefit }}
@empty
- Structured dossier preparation aligned with 2026 French administrative expectations.
@endforelse

## FAQ
@forelse((array) $faq as $item)
### {{ $item['q'] ?? '' }}
{{ $item['a'] ?? '' }}

@empty
No FAQ entries are published for this service yet. See [llms.txt]({{ url('/llms.txt') }}) for routing.
@endforelse

## Related pages
- [All services]({{ route('services.index', ['locale' => $locale]) }})
- [Budget / proof-of-funds calculator]({{ route('calculator', ['locale' => $locale]) }})
- [City guides]({{ route('cities.index', ['locale' => $locale]) }})
- [University guides]({{ route('universities.index', ['locale' => $locale]) }})

@include('markdown.partials.footer')
