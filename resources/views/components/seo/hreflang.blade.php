@props(['hreflang' => [], 'defaultLocale' => 'en'])

{{-- Hreflang Tags for Multilingual SEO --}}
@foreach($hreflang as $locale => $url)
    <link rel="alternate" hreflang="{{ $locale }}" href="{{ $url }}">
@endforeach

{{--
    x-default: points to the English version as the universal fallback.
    Google uses x-default for users whose locale isn't explicitly covered (e.g. Dutch users).
    Pointing to /fa/ would incorrectly serve Persian to non-Persian speakers.
    We always use 'en' regardless of $defaultLocale (which is used for internal site logic).
--}}
@if(isset($hreflang['en']))
    <link rel="alternate" hreflang="x-default" href="{{ $hreflang['en'] }}">
@elseif(isset($hreflang[$defaultLocale]))
    <link rel="alternate" hreflang="x-default" href="{{ $hreflang[$defaultLocale] }}">
@endif
