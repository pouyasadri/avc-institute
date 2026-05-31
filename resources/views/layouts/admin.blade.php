<!DOCTYPE html>
<html lang="fr" dir="ltr">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - @yield('title')</title>
    {{-- Vite-bundled CSS: Bootstrap 5 + app.scss variables --}}
    @vite(['resources/sass/app.scss', 'resources/js/app.js'])
    {{-- Admin-specific stylesheet (no Vite processing needed — pure CSS, no Sass) --}}
    <link rel="stylesheet" href="{{ asset('assets/css/boxicons.min.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/admin.css') }}">
    @stack('styles')
</head>

<body>

    <div class="admin-sidebar">
        <div class="admin-sidebar-header">
            <h3>Admin Panel</h3>
        </div>
        <div class="admin-nav-item">
            <a href="{{ route('admin.dashboard') }}"
                class="admin-nav-link {{ request()->routeIs('admin.dashboard') ? 'active' : '' }}">
                <i class='bx bxs-dashboard'></i> Tableau de bord
            </a>
        </div>
        <div class="admin-nav-item">
            <a href="{{ route('admin.blog.index') }}"
                class="admin-nav-link {{ request()->routeIs('admin.blog.*') ? 'active' : '' }}">
                <i class='bx bxs-news'></i> Blogs
            </a>
        </div>
        <div class="admin-nav-item">
            <a href="{{ route('admin.questions.index') }}"
                class="admin-nav-link {{ request()->routeIs('admin.questions.*') ? 'active' : '' }}">
                <i class='bx bxs-help-circle'></i> Questions
            </a>
        </div>
        <div class="admin-nav-item">
            <a href="{{ route('admin.comments.index') }}"
                class="admin-nav-link {{ request()->routeIs('admin.comments.*') ? 'active' : '' }}">
                <i class='bx bxs-comment'></i> Commentaires
            </a>
        </div>
        <div class="admin-nav-item">
            <a href="{{ route('admin.consulting.index') }}"
                class="admin-nav-link {{ request()->routeIs('admin.consulting.*') ? 'active' : '' }}">
                <i class='bx bxs-user-voice'></i> Consultations
            </a>
        </div>
        <div class="admin-nav-item">
            <a href="{{ route('admin.admins.index') }}"
                class="admin-nav-link {{ request()->routeIs('admin.admins.*') ? 'active' : '' }}">
                <i class='bx bxs-user-badge'></i> Administrateurs
            </a>
        </div>

        <div class="mt-auto pt-4 px-3 border-top border-secondary">
            <a href="{{ url('/') }}" class="admin-nav-link text-warning">
                <i class='bx bx-home'></i> Retour au site
            </a>
        </div>
    </div>

    <div class="admin-content">
        <div class="admin-header">
            <h4>@yield('header')</h4>
            <div class="user-info dropdown">
                <a href="#" class="d-flex align-items-center text-decoration-none dropdown-toggle text-dark"
                    id="dropdownUser1" data-bs-toggle="dropdown" aria-expanded="false">
                    <div class="user-avatar me-2">
                        {{ substr(Auth::user()->name, 0, 1) }}
                    </div>
                    <span>{{ Auth::user()->name }}</span>
                </a>
                <ul class="dropdown-menu dropdown-menu-end text-small shadow" aria-labelledby="dropdownUser1">
                    <li>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="dropdown-item text-danger">
                                <i class='bx bx-log-out'></i> Déconnexion
                            </button>
                        </form>
                    </li>
                </ul>
            </div>
        </div>

        @if(session('success'))
            <div class="alert alert-success alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class='bx bx-check-circle me-1'></i> {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if(session('error'))
            <div class="alert alert-danger alert-dismissible fade show border-0 shadow-sm" role="alert">
                <i class='bx bx-error-circle me-1'></i> {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @yield('content')
    </div>

    {{-- Vite-bundled JS already loaded in <head> via @vite above --}}
    <script>
        var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'))
        var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
            return new bootstrap.Tooltip(tooltipTriggerEl)
        })
    </script>
    @stack('scripts')
</body>

</html>