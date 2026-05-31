/**
 * theme-ltr.js — LTR theme entry point (en, fr locales)
 *
 * Imports are ordered: global polyfills → jQuery → jQuery plugins → app code.
 * Vite + Rollup will tree-shake and chunk this into vendor-jquery, vendor, and app bundles.
 */

// ─── Global jQuery (required by all plugins below) ───────────────────────────
import './jquery-global.js';
import 'jquery-migrate';

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

// ─── Plugins without npm packages (now bundled locally) ────────────────────────
import './vendor/meanmenu.min.js';
import './vendor/nice-select.min.js';
import './vendor/ofi.min.js';
import './vendor/ajaxchimp.min.js';
import './vendor/appear.min.js';
import './vendor/jquery.mixitup.min.js';
import './vendor/form-validator.min.js';
import './vendor/contact-form-script.js';

// ─── Custom LTR theme logic ───────────────────────────────────────────────────
import './custom-ltr.js';
