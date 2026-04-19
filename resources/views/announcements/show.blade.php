@extends('layouts.app')

@section('title', $announcement->title . ' – PetitesAnnonces')

@section('content')
<div class="container py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            @if($announcement->category->parent)
                <li class="breadcrumb-item">
                    <a href="{{ route('categories.show', $announcement->category->parent->slug) }}">
                        {{ $announcement->category->parent->name }}
                    </a>
                </li>
            @endif
            <li class="breadcrumb-item">
                <a href="{{ route('categories.show', $announcement->category->slug) }}">
                    {{ $announcement->category->name }}
                </a>
            </li>
            <li class="breadcrumb-item active text-truncate" style="max-width:200px">{{ $announcement->title }}</li>
        </ol>
    </nav>

    <div class="row g-4">

        {{-- ── Left column ─────────────────────────────────────────────── --}}
        <div class="col-lg-8">
            <div class="card border-0 shadow-sm rounded-3 overflow-hidden mb-4">

                {{-- Image gallery using image_urls accessor --}}
                @php $imageUrls = $announcement->image_urls; @endphp

                @if(count($imageUrls) > 0)
                    <div id="imgCarousel" class="carousel slide" data-bs-ride="carousel">
                        <div class="carousel-inner">
                            @foreach($imageUrls as $i => $url)
                                <div class="carousel-item {{ $i === 0 ? 'active' : '' }}">
                                    <img src="{{ $url }}"
                                         alt="Photo {{ $i + 1 }}"
                                         class="d-block w-100"
                                         style="max-height:420px; object-fit:cover;">
                                </div>
                            @endforeach
                        </div>
                        @if(count($imageUrls) > 1)
                            <button class="carousel-control-prev" type="button"
                                    data-bs-target="#imgCarousel" data-bs-slide="prev">
                                <span class="carousel-control-prev-icon"></span>
                            </button>
                            <button class="carousel-control-next" type="button"
                                    data-bs-target="#imgCarousel" data-bs-slide="next">
                                <span class="carousel-control-next-icon"></span>
                            </button>
                        @endif
                    </div>
                @else
                    <div class="bg-light d-flex align-items-center justify-content-center" style="height:280px">
                        <div class="text-center text-muted">
                            <i class="bi bi-image" style="font-size:5rem"></i>
                            <p class="mt-2">Aucune photo disponible</p>
                        </div>
                    </div>
                @endif

                <div class="card-body p-4">
                    {{-- Title & status --}}
                    <div class="d-flex justify-content-between align-items-start mb-2 gap-2">
                        <h1 class="fs-4 fw-bold mb-0">{{ $announcement->title }}</h1>
                        <span class="badge bg-{{ $announcement->status_color }} text-nowrap">
                            {{ $announcement->status_label }}
                        </span>
                    </div>

                    {{-- Price --}}
                    <div class="mb-3">
                        <span class="fs-3 fw-bold text-primary">{{ $announcement->formatted_price }}</span>
                        @if($announcement->is_negotiable)
                            <span class="badge bg-info text-dark ms-2">Négociable</span>
                        @endif
                    </div>

                    {{-- Meta badges --}}
                    <div class="d-flex flex-wrap gap-2 mb-4">
                        <span class="badge bg-light text-dark border">
                            <i class="bi bi-geo-alt-fill text-primary me-1"></i>{{ $announcement->city->name }}
                        </span>
                        <span class="badge bg-light text-dark border">
                            <i class="bi bi-tag-fill text-primary me-1"></i>{{ $announcement->category->name }}
                        </span>
                        <span class="badge bg-light text-dark border">
                            <i class="bi bi-stars text-warning me-1"></i>{{ $announcement->condition_label }}
                        </span>
                        <span class="badge bg-light text-dark border">
                            <i class="bi bi-eye me-1"></i>{{ number_format($announcement->views_count) }} vues
                        </span>
                        <span class="badge bg-light text-dark border">
                            <i class="bi bi-calendar3 me-1"></i>{{ $announcement->created_at->format('d/m/Y') }}
                        </span>
                    </div>

                    {{-- Description --}}
                    <h5 class="fw-semibold mb-2">Description</h5>
                    <div class="text-muted" style="white-space:pre-line">{{ $announcement->description }}</div>

                    {{-- Owner actions --}}
                    @if(auth()->id() === $announcement->user_id)
                        <hr>
                        <div class="d-flex gap-2 flex-wrap mt-3">
                            <a href="{{ route('announcements.edit', $announcement->slug) }}"
                               class="btn btn-outline-primary btn-sm">
                                <i class="bi bi-pencil me-1"></i>Modifier
                            </a>

                            <form action="{{ route('announcements.status', $announcement->slug) }}"
                                  method="POST" class="d-inline">
                                @csrf @method('PATCH')
                                @if($announcement->status !== 'sold')
                                    <input type="hidden" name="status" value="sold">
                                    <button class="btn btn-outline-secondary btn-sm">
                                        <i class="bi bi-check2-circle me-1"></i>Marquer vendu
                                    </button>
                                @else
                                    <input type="hidden" name="status" value="active">
                                    <button class="btn btn-outline-success btn-sm">
                                        <i class="bi bi-arrow-counterclockwise me-1"></i>Réactiver
                                    </button>
                                @endif
                            </form>

                            <form action="{{ route('announcements.destroy', $announcement->slug) }}"
                                  method="POST"
                                  onsubmit="return confirm('Supprimer définitivement cette annonce ?')">
                                @csrf @method('DELETE')
                                <button class="btn btn-outline-danger btn-sm">
                                    <i class="bi bi-trash me-1"></i>Supprimer
                                </button>
                            </form>
                        </div>
                    @endif
                </div>
            </div>

            {{-- Similar announcements --}}
            @if($similar->isNotEmpty())
            <div class="card border-0 shadow-sm rounded-3 p-4">
                <h5 class="fw-semibold mb-3">Annonces similaires</h5>
                <div class="row g-3">
                    @foreach($similar as $sim)
                        @include('partials.announcement-card', ['announcement' => $sim])
                    @endforeach
                </div>
            </div>
            @endif
        </div>

        {{-- ── Right column: seller + contact ─────────────────────────── --}}
        <div class="col-lg-4">

            {{-- Seller card --}}
            <div class="card border-0 shadow-sm rounded-3 mb-3">
                <div class="card-body p-4">
                    <h6 class="fw-semibold mb-3 text-muted text-uppercase small">Vendeur</h6>
                    <div class="d-flex align-items-center gap-3 mb-3">
                        <img src="{{ $announcement->user->avatar_url }}"
                             alt="Avatar"
                             class="rounded-circle"
                             width="54" height="54"
                             style="object-fit:cover">
                        <div>
                            <p class="fw-bold mb-0">{{ $announcement->user->name }}</p>
                            <small class="text-muted">
                                <i class="bi bi-geo-alt me-1"></i>
                                {{ $announcement->user->city->name ?? 'N/A' }}
                            </small>
                        </div>
                    </div>
                    <div class="d-flex justify-content-around text-center border rounded-3 p-2">
                        <div>
                            <div class="fw-bold text-primary">
                                {{ $announcement->user->active_announcements_count }}
                            </div>
                            <small class="text-muted">Annonces</small>
                        </div>
                        <div>
                            <div class="fw-bold text-primary">
                                {{ $announcement->user->created_at->format('Y') }}
                            </div>
                            <small class="text-muted">Membre depuis</small>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Contact form --}}
            @if($announcement->status === 'active')
                @auth
                    @if(auth()->id() !== $announcement->user_id)
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-4">
                            <h6 class="fw-semibold mb-3">
                                <i class="bi bi-chat-dots-fill text-primary me-2"></i>Contacter le vendeur
                            </h6>
                            <form action="{{ route('messages.store') }}" method="POST">
                                @csrf
                                <input type="hidden" name="announcement_id" value="{{ $announcement->id }}">
                                <div class="mb-3">
                                    <textarea name="body" rows="4"
                                              class="form-control @error('body') is-invalid @enderror"
                                              placeholder="Bonjour, est-ce que l'article est toujours disponible ?">{{ old('body') }}</textarea>
                                    @error('body')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                                <button class="btn btn-primary w-100 fw-semibold">
                                    <i class="bi bi-send me-2"></i>Envoyer le message
                                </button>
                            </form>
                        </div>
                    </div>
                    @endif
                @else
                    <div class="card border-0 shadow-sm rounded-3">
                        <div class="card-body p-4 text-center">
                            <i class="bi bi-lock fs-2 text-muted mb-2 d-block"></i>
                            <p class="small text-muted mb-3">Connectez-vous pour contacter le vendeur.</p>
                            <a href="{{ route('login') }}" class="btn btn-primary w-100">Se connecter</a>
                            <a href="{{ route('register') }}" class="btn btn-outline-primary w-100 mt-2">
                                Créer un compte
                            </a>
                        </div>
                    </div>
                @endauth
            @else
                <div class="alert alert-secondary text-center">
                    <i class="bi bi-x-circle me-1"></i>Cette annonce n'est plus disponible.
                </div>
            @endif
        </div>
    </div>
</div>
@endsection
