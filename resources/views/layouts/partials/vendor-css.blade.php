{{--
    vendor-css.blade.php
    Static vendor CSS that cannot be bundled by Vite because:
      - They reference font files via relative paths (e.g. ../fonts/) that would
        break when moved out of their current public/assets/css/ location.
      - No npm package exists for them.

    Variables:
      $isRtl — bool  (passed from main.blade.php via @include)
--}}

{{-- Bootstrap — RTL uses the pre-compiled RTL build; LTR uses the pre-compiled LTR build.
     The compiled SCSS bootstrap is already in the Vite bundle (app-ltr.scss).
     For RTL we use the pre-compiled bootstrap.rtl.min.css. --}}
@if($isRtl)
    <link rel="stylesheet" href="{{ asset('assets/css/bootstrap.rtl.min.css') }}">
@endif

{{-- Icon font sets (no npm equivalent; relative font paths would break under Vite) --}}
<link rel="stylesheet" href="{{ asset('assets/css/boxicons.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/flaticon.css') }}">

{{-- Utility/plugin CSS without npm packages --}}
<link rel="stylesheet" href="{{ asset('assets/css/meanmenu.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/nice-select.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/date-picker.min.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/beautiful-fonts.css') }}">
<link rel="stylesheet" href="{{ asset('assets/css/Vazirmatn-RD-FD-font-face.css') }}">

{{-- Theme-specific main stylesheets --}}
@if($isRtl)
    <link rel="stylesheet" href="{{ asset('assets/css/style-rtl.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/rtl.css') }}">
@else
    <link rel="stylesheet" href="{{ asset('assets/css/style-ltr.css') }}">
@endif
<link rel="stylesheet" href="{{ asset('assets/css/responsive.css') }}">
