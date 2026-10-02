@php
    $locale = $markdownLocale ?? app()->getLocale();
    $slug = $citySlug ?? '';
    $t = fn (string $key, mixed $default = '') => trans("city/{$slug}.{$key}") !== "city/{$slug}.{$key}"
        ? trans("city/{$slug}.{$key}")
        : $default;
    $title = $t('title', ucfirst($slug));
    $heading = $t('main_heading', $title);
    $introHeading = $t('intro_heading', $heading);
    $intro = $t('intro_paragraph', $t('description', ''));
    $quickFacts = $t('quick_facts', []);
@endphp
---
title: {{ $title }}
description: {{ $t('description', '') }}
locale: {{ $locale }}
canonical: {{ $markdownCanonical ?? url()->current() }}
city: {{ $slug }}
---

# {{ $heading }}

{{ $t('description', '') }}

Who this is for: students and families comparing French cities for study, housing cost, and settlement in 2026.

## {{ $introHeading }}
{{ $intro }}

@if(is_array($quickFacts) && count($quickFacts))
## Key facts (2026 snapshot)
@foreach($quickFacts as $fact)
@if(is_array($fact) && isset($fact['label'], $fact['value']))
- **{{ $fact['label'] }}**: {{ $fact['value'] }}
@endif
@endforeach
@endif

## Student life
{{ $t('student_life_paragraph', '') }}

## Study & universities
{{ $t('study_paragraph', $t('universities_intro', '')) }}

## Related pages
- [All city guides]({{ route('cities.index', ['locale' => $locale]) }})
- [Services hub]({{ route('services.index', ['locale' => $locale]) }})
- [Budget calculator]({{ route('calculator', ['locale' => $locale]) }})
- [University guides]({{ route('universities.index', ['locale' => $locale]) }})

@include('markdown.partials.footer')
