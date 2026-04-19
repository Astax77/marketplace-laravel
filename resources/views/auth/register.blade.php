@extends('layouts.app')

@section('title', 'Inscription – PetitesAnnonces')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-6">
            <div class="text-center mb-4">
                <a href="{{ route('home') }}" class="text-decoration-none">
                    <span class="fs-3 fw-bold text-primary">
                        <i class="bi bi-tag-fill me-1"></i>PetitesAnnonces
                    </span>
                </a>
            </div>

            <div class="card border-0 shadow rounded-3">
                <div class="card-body p-4 p-md-5">
                    <h1 class="fs-4 fw-bold text-center mb-4">Créer un compte</h1>

                    <form action="{{ route('register') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-medium">Nom complet <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-person"></i></span>
                                <input type="text" name="name"
                                       class="form-control @error('name') is-invalid @enderror"
                                       value="{{ old('name') }}" placeholder="Votre nom" autofocus>
                                @error('name') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Adresse e-mail <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" placeholder="vous@exemple.ma">
                                @error('email') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="row g-3 mb-3">
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Téléphone</label>
                                <div class="input-group">
                                    <span class="input-group-text"><i class="bi bi-phone"></i></span>
                                    <input type="text" name="phone"
                                           class="form-control @error('phone') is-invalid @enderror"
                                           value="{{ old('phone') }}" placeholder="06XXXXXXXX">
                                    @error('phone') <div class="invalid-feedback">{{ $message }}</div> @enderror
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-medium">Ville</label>
                                <select name="city_id" class="form-select @error('city_id') is-invalid @enderror">
                                    <option value="">-- Choisir --</option>
                                    @foreach($cities as $city)
                                        <option value="{{ $city->id }}" {{ old('city_id') == $city->id ? 'selected' : '' }}>
                                            {{ $city->name }}
                                        </option>
                                    @endforeach
                                </select>
                                @error('city_id') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Mot de passe <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="Minimum 8 caractères">
                                @error('password') <div class="invalid-feedback">{{ $message }}</div> @enderror
                            </div>
                        </div>

                        <div class="mb-4">
                            <label class="form-label fw-medium">Confirmer le mot de passe <span class="text-danger">*</span></label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock-fill"></i></span>
                                <input type="password" name="password_confirmation"
                                       class="form-control" placeholder="Répétez le mot de passe">
                            </div>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">
                            <i class="bi bi-person-check me-2"></i>Créer mon compte
                        </button>
                    </form>

                    <hr class="my-4">
                    <p class="text-center small mb-0">
                        Déjà inscrit ?
                        <a href="{{ route('login') }}" class="fw-semibold text-primary text-decoration-none">
                            Se connecter
                        </a>
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
