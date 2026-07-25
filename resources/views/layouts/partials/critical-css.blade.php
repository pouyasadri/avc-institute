{{--
    critical-css.blade.php
    Inlined above-the-fold styles so the browser can paint the preloader, navbar,
    and hero section without waiting for any external CSS network request.
    Keep this file lean — only styles needed before the first visible paint.
--}}
<style>
    /* ── Reset / Base ──────────────────────────────────────────── */
    *, *::before, *::after { box-sizing: border-box; }
    html { scroll-behavior: smooth; }
    body {
        margin: 0;
        font-family: @if('fa' === app()->getLocale()) 'Vazirmatn RD FD', @endif sans-serif;
        background: #fff;
        color: #333;
        overflow-x: hidden;
    }


    /* ── Navbar shell — prevents layout shift while full CSS loads ─ */
    .eorik-nav-style-four { position: fixed; top: 0; width: 100%; z-index: 9999; background: transparent; }
    .eorik-nav-style-four.is-sticky { background: rgba(0,0,0,.85); box-shadow: 0 2px 28px rgba(0,0,0,.06); }
    .navbar-area { background: transparent; }
    .main-nav { display: block; }
    .mobile-nav { display: none; }
    .navbar-brand img { max-width: 8rem; height: auto; }

    /* ── Hero / Slider shell — prevents blank white flash ─────────── */
    .eorik-slider-area { position: relative; overflow: hidden; background: #111; }
    .eorik-slider-item {
        min-height: 100vh;
        background-position: center center;
        background-size: cover;
        background-repeat: no-repeat;
        display: flex;
        align-items: center;
    }
    .d-table  { width: 100%; height: 100%; }
    .d-table-cell { display: table-cell; vertical-align: middle; }
    .eorik-slider-text { padding: 2rem 0; }
    .eorik-slider-text h1 {
        color: #fff;
        font-size: clamp(1.5rem, 4vw, 3rem);
        font-weight: 700;
        line-height: 1.25;
        margin-bottom: 1rem;
    }
    .eorik-slider-text span { color: #fff; display: block; margin-bottom: 1.5rem; }

    /* ── Skip link ────────────────────────────────────────────── */
    .skip-to-content {
        position: absolute;
        top: -40px;
        left: 0;
        background: #cc8c18;
        color: #fff;
        padding: .5rem 1rem;
        z-index: 100000;
        transition: top .2s;
    }
    .skip-to-content:focus { top: 0; }

    /* ── Utility ──────────────────────────────────────────────── */
    .container { width: 100%; padding: 0 15px; margin: 0 auto; max-width: 1200px; }
    img { max-width: 100%; height: auto; }

    /* ── Mobile breakpoint ────────────────────────────────────── */
    @media (max-width: 991px) {
        .main-nav  { display: none; }
        /* No background here — the parent .eorik-nav-style-four handles it
           (transparent by default, dark via .is-sticky added by scroll JS). */
        .mobile-nav { display: flex; justify-content: space-between; align-items: center; padding: .75rem 1rem; }
        .mobile-nav .logo img { width: 4rem !important; padding-bottom: 0 !important; }
        .mobile-menu-toggle { background: none; border: none; cursor: pointer; color: #fff; font-size: 1.75rem; line-height: 1; }
        .eorik-slider-text h1 { font-size: 1.5rem; }
    }
</style>
