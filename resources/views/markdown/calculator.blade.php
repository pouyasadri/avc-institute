@php
    $locale = $markdownLocale ?? app()->getLocale();
@endphp
---
title: {{ __('calculator.title') }}
description: {{ __('calculator.subtitle') }}
locale: {{ $locale }}
canonical: {{ $markdownCanonical ?? url()->current() }}
---

# {{ __('calculator.title') }}

{{ __('calculator.subtitle') }}

Who this is for: students and families estimating French VLS-TS proof of funds, CAF housing aid, tuition, and monthly living costs for 2026.

## What this calculator estimates
- {{ __('calculator.official_proof_title') }} — {{ __('calculator.official_proof_desc') }}
- {{ __('calculator.recommended_proof_title') }} — {{ __('calculator.recommended_proof_desc') }}
- {{ __('calculator.caf_subsidy_title') }} — {{ __('calculator.caf_subsidy_desc') }}
- {{ __('calculator.net_monthly_title') }}
- {{ __('calculator.first_year_total_title') }}

## Key regulatory note (2026)
{{ __('calculator.disclaimer') }}

{{ __('calculator.advisory_text') }}

## Recommended next steps
1. Run the interactive calculator on this page with your target city and accommodation type.
2. Compare results with the [student visa service]({{ route('services.show', ['locale' => $locale, 'slug' => 'student-visa']) }}).
3. Review housing / CAF guidance: [housing assistance]({{ route('services.show', ['locale' => $locale, 'slug' => 'housing-assistance']) }}).
4. Book a consultation before submitting consular financial documents.

@include('markdown.partials.footer')
