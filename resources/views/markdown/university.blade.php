@php
    $locale = $markdownLocale ?? app()->getLocale();
    $slug = $universitySlug ?? '';
    $t = fn (string $key, mixed $default = '') => trans("university/{$slug}.{$key}") !== "university/{$slug}.{$key}"
        ? trans("university/{$slug}.{$key}")
        : $default;
    $title = $t('title', ucfirst(str_replace('-', ' ', $slug)));
    $heading = $t('page_title', $t('main_heading', $title));
    $intro = $t('intro_content', $t('intro_paragraph', $t('description', '')));
    $subjects = $t('subjects', []);
@endphp
---
title: {{ $title }}
description: {{ $t('description', '') }}
locale: {{ $locale }}
canonical: {{ $markdownCanonical ?? url()->current() }}
university: {{ $slug }}
---

# {{ $heading }}

{{ $t('description', '') }}

Who this is for: international applicants researching French university admissions, Campus France pathways, and city fit for 2026.

## Overview
{{ $intro }}

@if(is_array($subjects) && count($subjects))
## Programs / focus areas
@foreach($subjects as $subject)
- {{ is_string($subject) ? $subject : ($subject['name'] ?? '') }}
@endforeach
@endif

## Admissions notes
{{ $t('admission_content', $t('admission_paragraph', 'Review official admissions pages and prepare Campus France / e-Candidat / Parcoursup dossiers carefully for 2026 intake cycles.')) }}

## Career / outcomes
{{ $t('career_content', $t('career_paragraph', '')) }}

## Related pages
- [All university guides]({{ route('universities.index', ['locale' => $locale]) }})
- [University application service]({{ route('services.show', ['locale' => $locale, 'slug' => 'university-application']) }})
- [Student visa service]({{ route('services.show', ['locale' => $locale, 'slug' => 'student-visa']) }})
- [City guides]({{ route('cities.index', ['locale' => $locale]) }})

@include('markdown.partials.footer')
