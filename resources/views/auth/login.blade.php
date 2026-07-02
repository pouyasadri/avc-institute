@extends('layouts.auth')

@section('content')
    <style>
        body,
        html {
            height: 100%;
            margin: 0;
            overflow: hidden;
        }

        .login-container {
            height: 100vh;
            display: flex;
            width: 100%;
        }

        .login-image {
            flex: 1.2;
            background-image: url("{{ asset('assets/img/louvre.webp') }}");
            background-size: cover;
            background-position: center;
            position: relative;
            display: none;
            /* Hidden on mobile */
        }

        .login-image::before {
            content: "";
            position: absolute;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: linear-gradient(135deg, rgba(79, 70, 229, 0.4), rgba(0, 0, 0, 0.6));
        }

        .login-form-container {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            background-color: #ffffff;
            padding: 40px;
            position: relative;
            z-index: 10;
            box-shadow: -10px 0 30px rgba(0, 0, 0, 0.05);
        }

        .login-form-wrapper {
            width: 100%;
            max-width: 400px;
        }

        .login-logo {
            text-align: center;
            margin-bottom: 2.5rem;
        }

        .login-logo img {
            max-width: 160px;
            height: auto;
        }

        .form-control {
            padding: 14px 18px;
            border-radius: 12px;
            border: 1px solid #e2e8f0;
            margin-bottom: 0.5rem;
            background-color: #f8fafc;
            transition: all 0.2s ease;
        }

        .form-control:focus {
            background-color: #fff;
            border-color: #4f46e5;
            box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.1);
        }

        .btn-primary {
            background: linear-gradient(to right, #4f46e5, #4338ca);
            border: none;
            padding: 14px;
            border-radius: 12px;
            font-weight: 600;
            width: 100%;
            margin-top: 1.5rem;
            transition: transform 0.2s, box-shadow 0.2s;
            color: white;
        }

        .btn-primary:hover {
            transform: translateY(-1px);
            box-shadow: 0 4px 12px rgba(79, 70, 229, 0.3);
            background: linear-gradient(to right, #4338ca, #3730a3);
        }

        .form-check-input:checked {
            background-color: #4f46e5;
            border-color: #4f46e5;
        }

        .login-footer {
            margin-top: 2rem;
            text-align: center;
            border-top: 1px solid #f1f5f9;
            padding-top: 1.5rem;
        }

        /* Desktop styles */
        @media (min-width: 992px) {
            .login-image {
                display: block;
            }
        }
    </style>

    <div class="login-container">
        <div class="login-image">
            <div class="position-absolute bottom-0 start-0 p-5 text-white">
                <h1 class="display-4 fw-bold">Apply VIP Conseil</h1>
                <p class="lead opacity-75">Panel d'administration pour la gestion des visas et de l'éducation en France.</p>
            </div>
        </div>
        <div class="login-form-container">
            <div class="login-form-wrapper">
                <div class="login-logo">
                    <a href="{{ url('/') }}">
                        <img src="{{ asset('assets/img/logo/new-logo.webp') }}" alt="Logo" width="224" height="150">
                    </a>
                </div>

                <h2 class="mb-4 fw-bold text-dark text-center">Connexion Admin</h2>
                <p class="text-muted mb-4 text-center">Accédez à votre tableau de bord sécurisé.</p>

                <form method="POST" action="{{ route('login') }}">
                    @csrf
                    <input type="hidden" name="locale" value="fr">

                    <div class="mb-4">
                        <label for="email" class="form-label fw-bold small text-uppercase text-secondary">Identifiant /
                            Email</label>
                        <input id="email" type="email" class="form-control @error('email') is-invalid @enderror"
                            name="email" value="{{ old('email') }}" required autocomplete="email" autofocus
                            placeholder="votre@email.com">
                        @error('email')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-4">
                        <label for="password" class="form-label fw-bold small text-uppercase text-secondary">Mot de
                            passe</label>
                        <input id="password" type="password" class="form-control @error('password') is-invalid @enderror"
                            name="password" required autocomplete="current-password" placeholder="••••••••">
                        @error('password')
                            <span class="invalid-feedback" role="alert">
                                <strong>{{ $message }}</strong>
                            </span>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <div class="form-check">
                            <input class="form-check-input" type="checkbox" name="remember" id="remember" {{ old('remember') ? 'checked' : '' }}>
                            <label class="form-check-label text-muted small" for="remember">
                                Rester connecté
                            </label>
                        </div>
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Se connecter <i class='bx bx-log-in ms-2'></i>
                    </button>

                    <div class="login-footer">
                        <a href="{{ url('/') }}"
                            class="text-secondary text-decoration-none small d-flex align-items-center justify-content-center">
                            <i class='bx bx-left-arrow-alt fs-4 me-1'></i> Retour au site principal
                        </a>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
