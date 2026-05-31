/**
 * theme-rtl.js — RTL theme entry point (fa/Persian locale)
 *
 * Identical plugin imports to theme-ltr.js; Vite's manualChunks will deduplicate
 * vendor code into shared chunks. Only the custom app logic differs (custom-rtl.js).
 */

// ─── Global jQuery ────────────────────────────────────────────────────────────
import './jquery-global.js';

// ─── Bootstrap JS ────────────────────────────────────────────────────────────
import 'bootstrap';

// ─── Owl Carousel ────────────────────────────────────────────────────────────
import 'owl.carousel';

// ─── Magnific Popup ──────────────────────────────────────────────────────────
import 'magnific-popup';

// ─── WOW.js ──────────────────────────────────────────────────────────────────
import WOW from 'wow.js';
window.WOW = WOW;

// ─── Jarallax ────────────────────────────────────────────────────────────────
import { jarallax } from 'jarallax';
window.jarallax = jarallax;

// ─── Odometer ────────────────────────────────────────────────────────────────
import Odometer from 'odometer';
window.Odometer = Odometer;

// ─── Bootstrap Datepicker ────────────────────────────────────────────────────
import 'bootstrap-datepicker';

// ─── Slick Carousel ──────────────────────────────────────────────────────────
// CSS imported here (not in SCSS) to avoid the @charset incompatibility
import 'slick-carousel/slick/slick.css';
import 'slick-carousel/slick/slick-theme.css';
import 'slick-carousel';

// ─── Custom RTL theme logic ───────────────────────────────────────────────────
import './custom-rtl.js';
