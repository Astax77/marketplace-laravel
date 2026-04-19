@extends('layouts.app')

@section('title', 'Mon Profil – ' . auth()->user()->name)

@section('content')
<div class="container py-4">
    <div class="row g-4">

        {{-- Sidebar --}}
        <div class="col-md-3">
            <div class="card border-0 shadow-sm rounded-3 text-center p-4 mb-3">
                <img src="{{ $user->avatar_url }}" alt="Avatar"
                     class="rounded-circle mx-auto mb-3 shadow" width="90" height="90" style="object-fit:cover">
                <h5 class="fw-bold mb-0">{{ $user->name }}</h5>
                <small class="text-muted">
                    <i class="bi bi-geo-alt me-1"></i>{{ $user->city->name ?? 'Ville non renseignée' }}
                </small>
                <hr>
                <div class="row text-center g-2">
                    <div class="col-6">
                        <div class="fw-bold text-primary fs-5">{{ $user->active_announcements_count }}</div>
                        <small class="text-muted">Annonces actives</small>
                    </div>
                    <div class="col-6">
                        <div class="fw-bold text-primary fs-5">{{ $user->created_at->format('Y') }}</div>
                        <small class="text-muted">Membre depuis</small>
                    </div>
                </div>
            </div>

            {{-- Nav links --}}
            <div class="list-group list-group-flush rounded-3 shadow-sm">
                <a href="{{ route('profile.show') }}" class="list-group-item list-group-item-action active">
                    <i class="bi bi-person me-2"></i>Mon profil
                </a>
                <a href="{{ route('profile.announcements') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-grid me-2"></i>Mes annonces
                </a>
                <a href="{{ route('messages.index') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-chat-dots me-2"></i>Messagerie
                </a>
                <a href="{{ route('profile.edit') }}" class="list-group-item list-group-item-action">
                    <i class="bi bi-pencil me-2"></i>Modifier le profil
                </a>
            </div>
        </div>

        {{-- Main content --}}
        <div class="col-md-9">
            <div class="card border-0 shadow-sm rounded-3 mb-3">
                <div class="card-body p-4">
                    <div class="d-flex justify-content-between align-items-center mb-4">
                        <h4 class="fw-bold mb-0">Informations personnelles</h4>
                        <a href="{{ route('profile.edit') }}" class="btn btn-outline-primary btn-sm">
                            <i class="bi bi-pencil me-1"></i>Modifier
                        </a>
                    </div>
                    <dl class="row">
                        <dt class="col-sm-4 text-muted">Nom complet</dt>
                        <dd class="col-sm-8">{{ $user->name }}</dd>

                        <dt class="col-sm-4 text-muted">E-mail</dt>
                        <dd class="col-sm-8">{{ $user->email }}</dd>

                        <dt class="col-sm-4 text-muted">Téléphone</dt>
                        <dd class="col-sm-8">{{ $user->phone ?? '<span class="text-muted">Non renseigné</span>' }}</dd>

                        <dt class="col-sm-4 text-muted">Ville</dt>
                        <dd class="col-sm-8">{{ $user->city->name ?? '–' }}</dd>

                        <dt class="col-sm-4 text-muted">Biographie</dt>
                        <dd class="col-sm-8">{{ $user->bio ?? '–' }}</dd>

                        <dt class="col-sm-4 text-muted">Membre depuis</dt>
                        <dd class="col-sm-8">{{ $user->created_at->translatedFormat('d MMMM Y') }}</dd>
                    </dl>
                </div>
            </div>

            {{-- Password change --}}
            <div class="card border-0 shadow-sm rounded-3">
                <div class="card-body p-4">
                    <h5 class="fw-semibold mb-3">
                        <i class="bi bi-shield-lock text-primary me-2"></i>Changer le mot de passe
                    </h5>
                    <form action="{{ route('profile.password') }}" method="POST">
                        @csrf @method('PUT')
                        <div class="row g-3">
                            <div class="col-md-4">
                                <label class="form-label small">Mot de passe actuel</label>
                                <input type="password" name="current_password"
                                       class="form-control @error('current_password') is-invalid @enderror">
                                @error('current_password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Nouveau mot de passe</label>
                                <input type="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror">
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                            <div class="col-md-4">
                                <label class="form-label small">Confirmer</label>
                                <input type="password" name="password_confirmation" class="form-control">
                            </div>
                        </div>
                        <button class="btn btn-outline-primary btn-sm mt-3">
                            <i class="bi bi-key me-1"></i>Mettre à jour
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
