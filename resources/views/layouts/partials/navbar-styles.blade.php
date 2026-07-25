{{-- Navbar and Mobile Menu Styles --}}
<style>
    :root {
        --primary-blue: #0F3A80;
        --accent-orange: #ff8c00ff;
        --secondary-teal: #5EC7D3;
        --text-dark: #0F3A80;
        --text-white: #ffffff;
        --glass-bg: rgba(255, 255, 255, 0.75);
        --glass-border: rgba(255, 255, 255, 0.5);
        --glass-shadow: 0 8px 32px 0 rgba(15, 58, 128, 0.15);
    }

    /* Force transparent backgrounds on all nav wrappers and states with high specificity to override !important rules in style-ltr.css / style-rtl.css */
    body .eorik-nav-style,
    body .eorik-nav-style-four,
    body .eorik-nav-style .navbar,
    body .eorik-nav-style-four .navbar,
    body .eorik-nav-style .main-nav,
    body .eorik-nav-style-four .main-nav,
    body .eorik-nav-style .navbar-area .main-nav,
    body .eorik-nav-style-four .navbar-area .main-nav,
    body .eorik-nav-style.is-sticky,
    body .eorik-nav-style-four.is-sticky,
    body .navbar-area.is-sticky .main-nav,
    body .eorik-nav-style .navbar-area.is-sticky .main-nav,
    body .eorik-nav-style-four .navbar-area.is-sticky .main-nav {
        background: transparent !important;
        background-color: transparent !important;
        backdrop-filter: none !important;
        -webkit-backdrop-filter: none !important;
        box-shadow: none !important;
        border: none !important;
    }

    .eorik-nav-style-four {
        transition: all 0.4s ease;
        position: fixed;
        top: 0;
        width: 100%;
        z-index: 1030;
        padding-top: 20px;
    }

    .navbar-area {
        background: var(--glass-bg);
        backdrop-filter: blur(16px) saturate(180%);
        -webkit-backdrop-filter: blur(16px) saturate(180%);
        border: 1px solid var(--glass-border);
        box-shadow: var(--glass-shadow);
        transition: all 0.4s ease;
        padding: 10px 0;
        margin: 0 auto;
        width: 90%;
        max-width: 1200px;
        border-radius: 50px;
    }

    /* Sticky State */
    .eorik-nav-style-four.is-sticky {
        padding-top: 10px;
    }

    .eorik-nav-style-four.is-sticky .navbar-area {
        background: rgba(255, 255, 255, 0.9) !important;
        backdrop-filter: blur(20px) saturate(200%);
        -webkit-backdrop-filter: blur(20px) saturate(200%);
        box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
        border: 1px solid rgba(255, 255, 255, 0.6);
        border-radius: 50px;
    }

    /* Navbar Links */
    .main-nav nav .navbar-nav .nav-item a {
        color: var(--text-dark) !important;
        font-family: 'Inter', sans-serif;
        font-weight: 500;
        font-size: 15px;
        padding: 10px 20px !important;
        margin: 0 4px;
        border-radius: 50px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    /* Hover Effect */
    .main-nav nav .navbar-nav .nav-item a:hover,
    .main-nav nav .navbar-nav .nav-item:hover a {
        color: var(--accent-orange) !important;
        background: rgba(255, 140, 0, 0.1);
        transform: translateY(-1px);
    }

    /* Active State */
    .main-nav nav .navbar-nav .nav-item a.active {
        background: var(--accent-orange) !important;
        color: var(--text-white) !important;
        font-weight: 600;
        border: none;
        box-shadow: none;
        transform: translateY(-1px);
    }

    /* Brand Logo */
    .navbar-brand img {
        transition: all 0.3s ease;
    }

    /* Language Switcher */
    .desktop-language-switcher {
        position: relative;
    }

    .desktop-language-switcher .dropdown {
        position: relative;
    }

    .desktop-language-switcher .lang-dropdown-toggle {
        background: rgba(15, 58, 128, 0.05);
        border: 1px solid rgba(15, 58, 128, 0.1);
        color: var(--text-dark);
        padding: 8px 16px;
        border-radius: 50px;
        font-size: 14px;
        font-weight: 600;
        display: flex;
        align-items: center;
        gap: 8px;
        transition: all 0.3s ease;
        cursor: pointer;
    }

    .desktop-language-switcher .lang-dropdown-toggle:hover {
        background: rgba(15, 58, 128, 0.1);
        color: var(--primary-blue);
    }

    .lang-dropdown-menu {
        position: absolute;
        top: 100%;
        right: 0;
        background: rgba(255, 255, 255, 0.95);
        backdrop-filter: blur(16px);
        border: 1px solid rgba(0, 0, 0, 0.05);
        border-radius: 12px;
        padding: 8px;
        margin-top: 10px;
        box-shadow: 0 10px 40px rgba(0, 0, 0, 0.1);
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px);
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        min-width: 150px;
        z-index: 1000;
        display: flex;
        flex-direction: column;
        gap: 4px;
    }

    .desktop-language-switcher .dropdown:hover .lang-dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0);
    }

    .lang-dropdown-menu a {
        text-decoration: none;
        color: var(--text-dark);
        border-radius: 8px;
        padding: 8px 16px;
        display: block;
        transition: all 0.2s ease;
    }

    .lang-dropdown-menu a:hover,
    .lang-dropdown-menu a.active {
        background: var(--accent-orange);
        color: white;
    }

    /* Mobile Menu Toggle */
    .mobile-menu-toggle {
        background: transparent;
        border: none;
        padding: 4px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        z-index: 10001;
        border-radius: 8px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
    }

    .mobile-menu-toggle i {
        font-size: 32px;
        color: var(--primary-blue);
        transition: all 0.3s ease;
    }

    .mobile-menu-toggle:hover {
        background: rgba(15, 58, 128, 0.08);
    }

    .mobile-menu-toggle:active {
        transform: scale(0.95);
    }

    .mobile-menu-toggle.active i {
        color: var(--accent-orange);
        transform: rotate(90deg);
    }

    /* Mobile Menu Backdrop */
    .mobile-menu-backdrop {
        position: fixed;
        top: 0;
        left: 0;
        width: 100%;
        height: 100%;
        background: rgba(0, 0, 0, 0.5);
        z-index: 1040;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .mobile-menu-backdrop.active {
        opacity: 1;
        visibility: visible;
    }

    /* Mobile Menu Drawer */
    .mobile-menu-drawer {
        position: fixed;
        top: 0;
        bottom: 0;
        right: 0;
        width: 280px;
        max-width: 85vw;
        background: white;
        box-shadow: -4px 0 20px rgba(0, 0, 0, 0.15);
        z-index: 1041;
        display: flex;
        flex-direction: column;
        transform: translateX(100%);
        transition: transform 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        overflow-y: auto;
    }

    .mobile-menu-drawer.active {
        transform: translateX(0);
    }

    /* Drawer Header */
    .mobile-menu-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 20px;
        border-bottom: 1px solid rgba(15, 58, 128, 0.1);
        flex-shrink: 0;
    }

    .mobile-menu-logo {
        flex: 1;
    }

    .mobile-menu-logo img {
        height: 40px;
        width: auto;
    }

    .mobile-menu-close {
        background: rgba(15, 58, 128, 0.1);
        border: none;
        width: 40px;
        height: 40px;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        flex-shrink: 0;
    }

    .mobile-menu-close i {
        font-size: 24px;
        color: var(--primary-blue);
        transition: all 0.3s ease;
    }

    .mobile-menu-close:hover {
        background: var(--accent-orange);
        transform: rotate(90deg) scale(1.05);
    }

    .mobile-menu-close:hover i {
        color: white;
    }

    .mobile-menu-close:active {
        transform: rotate(90deg) scale(0.95);
    }

    /* Drawer Body */
    .mobile-menu-body {
        flex: 1;
        overflow-y: auto;
        padding: 20px;
    }

    .mobile-menu-nav ul {
        list-style: none;
        padding: 0;
        margin: 0;
    }

    .mobile-menu-nav ul li {
        margin-bottom: 4px;
    }

    .mobile-menu-nav ul li a {
        color: var(--primary-blue);
        font-size: 16px;
        font-weight: 500;
        display: flex;
        align-items: center;
        padding: 14px 16px;
        border-radius: 12px;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        font-family: 'Inter', sans-serif;
    }

    .mobile-menu-nav ul li a:hover {
        background: rgba(15, 58, 128, 0.05);
        color: var(--primary-blue);
        transform: translateX(4px);
    }

    .mobile-menu-nav ul li a.active {
        background: rgba(255, 140, 0, 0.1);
        color: var(--accent-orange);
        font-weight: 600;
        border-left: 3px solid var(--accent-orange);
    }

    /* Drawer Footer */
    .mobile-menu-footer {
        padding: 20px;
        border-top: 1px solid rgba(15, 58, 128, 0.1);
        flex-shrink: 0;
    }

    .mobile-language-switcher {
        margin-bottom: 20px;
    }

    .mobile-language-switcher .language-label {
        color: var(--primary-blue);
        display: block;
        margin-bottom: 12px;
        font-size: 12px;
        font-weight: 600;
        text-transform: uppercase;
        letter-spacing: 0.5px;
        opacity: 0.6;
    }

    .mobile-language-switcher .language-buttons {
        display: flex;
        gap: 8px;
    }

    .mobile-language-switcher .lang-btn {
        color: var(--primary-blue);
        border: 1.5px solid rgba(15, 58, 128, 0.2);
        background: rgba(15, 58, 128, 0.05);
        padding: 8px 16px;
        border-radius: 8px;
        font-size: 14px;
        font-weight: 600;
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
        flex: 1;
        text-align: center;
    }

    .mobile-language-switcher .lang-btn:hover {
        border-color: var(--primary-blue);
        background: rgba(15, 58, 128, 0.1);
    }

    .mobile-language-switcher .lang-btn.active {
        background: var(--accent-orange);
        color: white;
        border-color: var(--accent-orange);
    }

    .mobile-menu-social {
        display: flex;
        gap: 12px;
    }

    .mobile-menu-social a {
        color: var(--primary-blue);
        font-size: 22px;
        width: 44px;
        height: 44px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 50%;
        background: rgba(15, 58, 128, 0.05);
        transition: all 0.2s cubic-bezier(0.4, 0, 0.2, 1);
    }

    .mobile-menu-social a:hover {
        color: white;
        background: var(--accent-orange);
        transform: translateY(-2px);
    }

    /* RTL Overrides */
    body.rtl .lang-dropdown-menu {
        right: auto;
        left: 0;
    }

    body.rtl .mobile-menu-drawer {
        right: auto;
        left: 0;
        transform: translateX(-100%);
        box-shadow: 4px 0 20px rgba(0, 0, 0, 0.15);
    }

    body.rtl .mobile-menu-drawer.active {
        transform: translateX(0);
    }

    body.rtl .mobile-menu-nav ul li a:hover {
        transform: translateX(-4px);
    }

    body.rtl .mobile-menu-nav ul li a.active {
        border-left: none;
        border-right: 3px solid var(--accent-orange);
    }

    /* Improved Persian Typography */
    body.rtl .main-nav nav .navbar-nav .nav-item a,
    body.rtl .mobile-menu-nav ul li a,
    body.rtl .mobile-language-switcher,
    body.rtl .desktop-language-switcher {
        font-family: "Vazirmatn RD FD", "Vazirmatn", sans-serif !important;
    }

    body.rtl .main-nav nav .navbar-nav .nav-item a {
        font-size: 16px;
        font-weight: 500;
        letter-spacing: 0;
    }

    body.rtl .mobile-menu-nav ul li a {
        font-size: 18px;
    }

    /* Focus States */
    .main-nav nav .navbar-nav .nav-item a:focus-visible,
    .mobile-menu-toggle:focus-visible {
        outline: 2px solid var(--accent-orange);
        outline-offset: 4px;
    }

    /* Prevent body scroll when menu is open */
    body.mobile-menu-open {
        overflow: hidden;
    }

    /* Optimize LTR desktop navbar layout to prevent overflow on English/French locales */
    body.ltr .navbar-area {
        width: 95%;
        max-width: 1280px;
    }
    body.ltr .main-nav nav .navbar-nav .nav-item a {
        font-family: 'Inter', sans-serif;
        font-size: 14px;
        padding: 8px 12px !important;
        margin: 0 2px;
    }
    body.ltr .navbar-consult-cta {
        padding: 8px 14px;
        font-size: 0.85rem;
        margin-left: 10px;
    }
</style>