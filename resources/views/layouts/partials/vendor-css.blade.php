{{--
    vendor-css.blade.php
    Static vendor CSS that cannot be bundled by Vite because:
      - They reference font files via relative paths (e.g. ../fonts/) that would
        break when moved out of their current public/assets/css/ location.
      - No npm package exists for them.

    ALL stylesheets here are loaded DEFERRED (non-render-blocking) via the
    media="print" / onload pattern. Critical above-the-fold styles are inlined
    separately via layouts/partials/critical-css.blade.php.

    Variables:
      $isRtl — bool  (passed from main.blade.php via @include)
--}}

{{-- Helper macro: deferred stylesheet loader --}}
@php
    $v = fn(string $file) => file_exists(public_path($file)) ? '?v=' . filemtime(public_path($file)) : '';
@endphp

{{-- Bootstrap RTL — deferred (21 KiB, was 1,110 ms render-blocking) --}}
@if($isRtl)
    <link rel="preload" href="{{ asset('assets/css/bootstrap.rtl.min.css') }}" as="style"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('assets/css/bootstrap.rtl.min.css') }}"></noscript>
@endif

{{-- Icon font sets — deferred (font-display:swap already set in the files) --}}
<link rel="preload" href="{{ asset('assets/css/boxicons.min.css') }}" as="style"
      onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset('assets/css/boxicons.min.css') }}"></noscript>

<link rel="preload" href="{{ asset('assets/css/flaticon.css') }}" as="style"
      onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset('assets/css/flaticon.css') }}"></noscript>

{{-- Utility/plugin CSS — deferred --}}
<link rel="preload" href="{{ asset('assets/css/meanmenu.min.css') }}" as="style"
      onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset('assets/css/meanmenu.min.css') }}"></noscript>

<link rel="preload" href="{{ asset('assets/css/nice-select.min.css') }}" as="style"
      onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset('assets/css/nice-select.min.css') }}"></noscript>

<link rel="preload" href="{{ asset('assets/css/date-picker.min.css') }}" as="style"
      onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset('assets/css/date-picker.min.css') }}"></noscript>

<link rel="preload" href="{{ asset('assets/css/beautiful-fonts.css') }}" as="style"
      onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset('assets/css/beautiful-fonts.css') }}"></noscript>

<link rel="preload" href="{{ asset('assets/css/Vazirmatn-RD-FD-font-face.css') }}" as="style"
      onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset('assets/css/Vazirmatn-RD-FD-font-face.css') }}"></noscript>

{{-- Theme-specific main stylesheets — deferred --}}
@if($isRtl)
    <link rel="preload" href="{{ asset('assets/css/style-rtl.css') }}{{ $v('assets/css/style-rtl.css') }}" as="style"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}{{ $v('assets/css/style-rtl.css') }}"></noscript>

    <link rel="preload" href="{{ asset('assets/css/rtl.css') }}{{ $v('assets/css/rtl.css') }}" as="style"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('assets/css/rtl.css') }}{{ $v('assets/css/rtl.css') }}"></noscript>
@else
    <link rel="preload" href="{{ asset('assets/css/style-ltr.css') }}{{ $v('assets/css/style-ltr.css') }}" as="style"
          onload="this.onload=null;this.rel='stylesheet'">
    <noscript><link rel="stylesheet" href="{{ asset('assets/css/style-ltr.css') }}{{ $v('assets/css/style-ltr.css') }}"></noscript>
@endif

<link rel="preload" href="{{ asset('assets/css/responsive.css') }}{{ $v('assets/css/responsive.css') }}" as="style"
      onload="this.onload=null;this.rel='stylesheet'">
<noscript><link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}{{ $v('assets/css/responsive.css') }}"></noscript>
