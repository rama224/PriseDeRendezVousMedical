<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>@yield('title', 'Accueil') · {{ config('app.name') }}</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet">
    <style>
        /* Lien du menu correspondant à la page en cours */
        .navbar .nav-link.active {
            font-weight: 600;
            box-shadow: inset 0 -3px 0 #fff;
        }
    </style>
</head>
<body class="bg-light">
    <nav class="navbar navbar-expand-md navbar-dark bg-primary mb-4">
        <div class="container">
            <a class="navbar-brand fw-semibold" href="{{ url('/') }}">{{ config('app.name') }}</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#menu" aria-controls="menu" aria-expanded="false" aria-label="Menu">
                <span class="navbar-toggler-icon"></span>
            </button>

            <div class="collapse navbar-collapse" id="menu">
                <ul class="navbar-nav ms-auto align-items-md-center">
                    @auth
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('rendez-vous.create', 'rendez-vous.confirmer') ? 'active' : '' }}"
                               @if (request()->routeIs('rendez-vous.create', 'rendez-vous.confirmer')) aria-current="page" @endif
                               href="{{ route('rendez-vous.create') }}">Prendre rendez-vous</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('rendez-vous.index') ? 'active' : '' }}"
                               @if (request()->routeIs('rendez-vous.index')) aria-current="page" @endif
                               href="{{ route('rendez-vous.index') }}">Mes rendez-vous</a>
                        </li>
                        <li class="nav-item dropdown ms-md-2">
                            <button class="btn btn-outline-light btn-sm dropdown-toggle" type="button" id="menu-compte"
                                    data-bs-toggle="dropdown" aria-expanded="false">
                                {{ Auth::user()->name }}
                            </button>
                            <div class="dropdown-menu dropdown-menu-end" aria-labelledby="menu-compte">
                                <div class="px-3 py-2">
                                    <div class="fw-semibold">{{ Auth::user()->name }}</div>
                                    <div class="small text-muted">{{ Auth::user()->email }}</div>
                                    <div class="small text-muted">Inscrit le {{ Auth::user()->created_at->translatedFormat('j F Y') }}</div>
                                </div>
                                <div class="dropdown-divider"></div>
                                <form method="POST" action="{{ route('logout') }}">
                                    @csrf
                                    <button type="submit" class="dropdown-item text-danger">Déconnexion</button>
                                </form>
                            </div>
                        </li>
                    @else
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('login') ? 'active' : '' }}"
                               @if (request()->routeIs('login')) aria-current="page" @endif
                               href="{{ route('login') }}">Connexion</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ request()->routeIs('register') ? 'active' : '' }}"
                               @if (request()->routeIs('register')) aria-current="page" @endif
                               href="{{ route('register') }}">Inscription</a>
                        </li>
                    @endauth
                </ul>
            </div>
        </div>
    </nav>

    <main class="container pb-5">
        @if (session('success'))
            <div class="alert alert-success">{{ session('success') }}</div>
        @endif

        @yield('content')
    </main>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
