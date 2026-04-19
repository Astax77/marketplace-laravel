@extends('layouts.app')

@section('title', 'Accueil – PetitesAnnonces Maroc')

@section('content')

{{-- ── Hero Section ────────────────────────────────────────────────────────── --}}
<section class="hero-section bg-primary text-white py-5">
    <div class="container">
        <div class="row justify-content-center text-center">
            <div class="col-lg-8">
                <h1 class="display-5 fw-bold mb-3">
                    Achetez et vendez au Maroc
                </h1>
                <p class="lead mb-4 opacity-75">Des milliers d'annonces partout au Maroc. Gratuit, simple et rapide.</p>

                <form action="{{ route('search') }}" method="GET" class="hero-search">
                    <div class="input-group input-group-lg shadow">
                        <input type="text" name="q" class="form-control form-control-lg border-0"
                               placeholder="Que recherchez-vous ?" value="{{ request('q') }}">
                        <select name="city_id" class="form-select border-start border-0" style="max-width:180px">
                            <option value="">Toutes les villes</option>
                            @foreach($popularCities as $city)
                                <option value="{{ $city->id }}">{{ $city->name }}</option>
                            @endforeach
                        </select>
                        <button class="btn btn-warning fw-bold px-4" type="submit">
                            <i class="bi bi-search me-1"></i>Chercher
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</section>

{{-- ── Categories ──────────────────────────────────────────────────────────── --}}
<section class="py-5">
    <div class="container">
        <h2 class="fs-4 fw-bold mb-4">
            <i class="bi bi-grid-3x3-gap text-primary me-2"></i>Parcourir les catégories
        </h2>
        <div class="row g-3">
            @foreach($categories as $cat)
            <div class="col-6 col-md-3 col-lg-2">
                <a href="{{ route('categories.show', $cat->slug) }}"
                   class="card card-hover text-center text-decoration-none h-100 border-0 shadow-sm rounded-3 p-3">
                    <div class="card-body p-0">
                        <div class="category-icon mb-2">
                            <i class="bi {{ $cat->icon ?? 'bi-tag' }} fs-1 text-primary"></i>
                        </div>
                        <p class="fw-semibold small mb-1 text-dark">{{ $cat->name }}</p>
                        <small class="text-muted">{{ $cat->announcements_count }} annonces</small>
                    </div>
                </a>
            </div>
            @endforeach
        </div>
    </div>
</section>

{{-- ── Latest Announcements ─────────────────────────────────────────────────── --}}
<section class="py-4 bg-white">
    <div class="container">
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="fs-4 fw-bold mb-0">
                <i class="bi bi-clock-history text-primary me-2"></i>Annonces récentes
            </h2>
            <a href="{{ route('announcements.index') }}" class="btn btn-outline-primary btn-sm">
                Voir tout <i class="bi bi-arrow-right ms-1"></i>
            </a>
        </div>

        <div class="row g-3">
            @forelse($latestAnnouncements as $ann)
                @include('partials.announcement-card', ['announcement' => $ann])
            @empty
                <div class="col-12 text-center py-5 text-muted">
                    <i class="bi bi-inbox fs-1 d-block mb-2"></i>
                    Aucune annonce pour le moment.
                </div>
            @endforelse
        </div>
    </div>
</section>

{{-- ── Cities ───────────────────────────────────────────────────────────────── --}}
<section class="py-5">
    <div class="container">
        <h2 class="fs-4 fw-bold mb-4">
            <i class="bi bi-geo-alt text-primary me-2"></i>Villes populaires
        </h2>
        <div class="d-flex flex-wrap gap-2">
            @foreach($popularCities as $city)
            <a href="{{ route('search', ['city_id' => $city->id]) }}"
               class="btn btn-outline-secondary btn-sm rounded-pill">
                {{ $city->name }}
                <span class="badge bg-primary rounded-pill ms-1">{{ $city->announcements_count }}</span>
            </a>
            @endforeach
        </div>
    </div>
</section>

{{-- ── CTA ──────────────────────────────────────────────────────────────────── --}}
@guest
<section class="bg-primary text-white py-5">
    <div class="container text-center">
        <h2 class="fw-bold mb-3">Prêt à vendre ?</h2>
        <p class="lead opacity-75 mb-4">Créez votre compte gratuitement et publiez votre première annonce en moins de 2 minutes.</p>
        <a href="{{ route('register') }}" class="btn btn-warning btn-lg fw-bold me-2">
            <i class="bi bi-person-plus me-2"></i>Créer un compte gratuit
        </a>
        <a href="{{ route('login') }}" class="btn btn-outline-light btn-lg">Se connecter</a>
    </div>
</section>
@endguest

@endsection
