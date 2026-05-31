/**
 * theme-ltr.js — LTR theme entry point (en, fr locales)
 *
 * Imports are ordered: global polyfills → jQuery → jQuery plugins → app code.
 * Vite + Rollup will tree-shake and chunk this into vendor-jquery, vendor, and app bundles.
 */

// ─── Global jQuery (required by all plugins below) ───────────────────────────
import $ from 'jquery';
import jQuery from 'jquery';
window.$ = $;
window.jQuery = jQuery;

// ─── Bootstrap JS (uses Popper via bundled build) ────────────────────────────
import 'bootstrap';

// ─── Owl Carousel ────────────────────────────────────────────────────────────
import 'owl.carousel';

// ─── Magnific Popup ──────────────────────────────────────────────────────────
import 'magnific-popup';

// ─── WOW.js (scroll-triggered animations) ────────────────────────────────────
import WOW from 'wow.js';
window.WOW = WOW;

// ─── Jarallax (parallax backgrounds) ─────────────────────────────────────────
import { jarallax } from 'jarallax';
window.jarallax = jarallax;

// ─── Odometer (animated number counters) ─────────────────────────────────────
import Odometer from 'odometer';
window.Odometer = Odometer;

// ─── Bootstrap Datepicker ────────────────────────────────────────────────────
import 'bootstrap-datepicker';

// ─── Slick Carousel ──────────────────────────────────────────────────────────
// CSS imported here (not in SCSS) to avoid the @charset incompatibility
import 'slick-carousel/slick/slick.css';
import 'slick-carousel/slick/slick-theme.css';
import 'slick-carousel';

// ─── Plugins without npm packages (remain as static files) ───────────────────
// meanmenu, nice-select, ofi (objectFitImages), ajaxchimp, appear, form-validator,
// contact-form-script, mixitup, createScrollLinks are loaded via @stack('scripts')
// or will remain as static assets until npm equivalents are available.

// ─── Custom LTR theme logic ───────────────────────────────────────────────────
import './custom-ltr.js';
