<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <title>@yield('title', config('app.name', 'PetitesAnnonces'))</title>

    {{-- Bootstrap 5 CSS --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css">
    {{-- Bootstrap Icons --}}
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    {{-- Custom CSS --}}
    <link rel="stylesheet" href="{{ asset('css/app.css') }}">

    @stack('styles')
</head>
<body class="bg-light">

{{-- ── Navbar ─────────────────────────────────────────────────────────────── --}}
<nav class="navbar navbar-expand-lg navbar-dark bg-primary sticky-top shadow-sm">
    <div class="container">
        <a class="navbar-brand fw-bold fs-4" href="{{ route('home') }}">
            <i class="bi bi-tag-fill me-1"></i>PetitesAnnonces
        </a>

        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navMain">
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navMain">

            {{-- Search bar --}}
            <form class="d-flex flex-grow-1 mx-3" action="{{ route('search') }}" method="GET">
                <div class="input-group">
                    <input type="text" name="q" class="form-control" placeholder="Rechercher..."
                           value="{{ request('q') }}" id="searchInput" autocomplete="off">
                    <button class="btn btn-warning fw-semibold" type="submit">
                        <i class="bi bi-search"></i>
                    </button>
                </div>
                {{-- Autocomplete dropdown --}}
                <ul class="list-group position-absolute mt-5 w-100 shadow z-3 d-none" id="autocomplete"></ul>
            </form>

            <ul class="navbar-nav ms-auto align-items-center gap-1">
                @auth
                    {{-- Post ad button --}}
                    <li class="nav-item">
                        <a href="{{ route('announcements.create') }}" class="btn btn-warning btn-sm fw-semibold">
                            <i class="bi bi-plus-circle me-1"></i>Déposer
                        </a>
                    </li>

                    {{-- Messages --}}
                    <li class="nav-item">
                        <a href="{{ route('messages.index') }}" class="nav-link position-relative px-2">
                            <i class="bi bi-chat-dots fs-5"></i>
                            @php $unread = app(\App\Services\MessageService::class)->getUnreadCount(auth()->user()); @endphp
                            @if($unread > 0)
                                <span class="position-absolute top-0 start-100 translate-middle badge rounded-pill bg-danger">
                                    {{ $unread > 99 ? '99+' : $unread }}
                                </span>
                            @endif
                        </a>
                    </li>

                    {{-- User dropdown --}}
                    <li class="nav-item dropdown">
                        <a class="nav-link dropdown-toggle d-flex align-items-center gap-2" href="#"
                           data-bs-toggle="dropdown">
                            <img src="{{ auth()->user()->avatar_url }}" alt="avatar"
                                 class="rounded-circle" width="32" height="32" style="object-fit:cover">
                            <span class="d-none d-lg-inline">{{ auth()->user()->name }}</span>
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end">
                            <li><a class="dropdown-item" href="{{ route('profile.show') }}">
                                <i class="bi bi-person me-2"></i>Mon profil
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('profile.announcements') }}">
                                <i class="bi bi-grid me-2"></i>Mes annonces
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li>
                                <form action="{{ route('logout') }}" method="POST">
                                    @csrf
                                    <button class="dropdown-item text-danger">
                                        <i class="bi bi-box-arrow-right me-2"></i>Déconnexion
                                    </button>
                                </form>
                            </li>
                        </ul>
                    </li>
                @else
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link">Connexion</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="btn btn-warning btn-sm fw-semibold">
                            S'inscrire
                        </a>
                    </li>
                @endauth
            </ul>
        </div>
    </div>
</nav>

{{-- ── Flash messages ─────────────────────────────────────────────────────── --}}
@if(session('success') || session('error'))
<div class="container mt-3">
    @if(session('success'))
        <div class="alert alert-success alert-dismissible fade show" role="alert">
            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
        </div>
    @endif
</div>
@endif

{{-- ── Page content ────────────────────────────────────────────────────────── --}}
<main>
    @yield('content')
</main>

{{-- ── Footer ─────────────────────────────────────────────────────────────── --}}
<footer class="bg-dark text-light py-5 mt-5">
    <div class="container">
        <div class="row g-4">
            <div class="col-md-4">
                <h5 class="fw-bold"><i class="bi bi-tag-fill me-2 text-warning"></i>PetitesAnnonces</h5>
                <p class="text-muted small">La plateforme marocaine de petites annonces entre particuliers. Achetez, vendez, échangez en toute confiance.</p>
            </div>
            <div class="col-md-2">
                <h6 class="fw-semibold">Navigation</h6>
                <ul class="list-unstyled small">
                    <li><a href="{{ route('home') }}" class="text-muted text-decoration-none">Accueil</a></li>
                    <li><a href="{{ route('announcements.index') }}" class="text-muted text-decoration-none">Annonces</a></li>
                    <li><a href="{{ route('search') }}" class="text-muted text-decoration-none">Recherche</a></li>
                </ul>
            </div>
            <div class="col-md-2">
                <h6 class="fw-semibold">Compte</h6>
                <ul class="list-unstyled small">
                    @auth
                        <li><a href="{{ route('profile.show') }}" class="text-muted text-decoration-none">Mon profil</a></li>
                        <li><a href="{{ route('profile.announcements') }}" class="text-muted text-decoration-none">Mes annonces</a></li>
                        <li><a href="{{ route('messages.index') }}" class="text-muted text-decoration-none">Messages</a></li>
                    @else
                        <li><a href="{{ route('login') }}" class="text-muted text-decoration-none">Connexion</a></li>
                        <li><a href="{{ route('register') }}" class="text-muted text-decoration-none">Inscription</a></li>
                    @endauth
                </ul>
            </div>
            <div class="col-md-4">
                <h6 class="fw-semibold">Déposer une annonce</h6>
                <p class="text-muted small">C'est gratuit et rapide. Rejoignez des milliers de vendeurs.</p>
                @auth
                    <a href="{{ route('announcements.create') }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-plus-circle me-1"></i>Déposer une annonce
                    </a>
                @else
                    <a href="{{ route('register') }}" class="btn btn-warning btn-sm">
                        <i class="bi bi-person-plus me-1"></i>Créer un compte
                    </a>
                @endauth
            </div>
        </div>
        <hr class="border-secondary mt-4">
        <p class="text-center text-muted small mb-0">
            &copy; {{ date('Y') }} PetitesAnnonces. Tous droits réservés.
        </p>
    </div>
</footer>

{{-- Scripts --}}
<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js"></script>
<script src="{{ asset('js/app.js') }}"></script>
@stack('scripts')

</body>
</html>
