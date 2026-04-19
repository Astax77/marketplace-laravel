@extends('layouts.app')

@section('title', 'Connexion – PetitesAnnonces')

@section('content')
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-md-5">
            <div class="text-center mb-4">
                <a href="{{ route('home') }}" class="text-decoration-none">
                    <span class="fs-3 fw-bold text-primary">
                        <i class="bi bi-tag-fill me-1"></i>PetitesAnnonces
                    </span>
                </a>
            </div>

            <div class="card border-0 shadow rounded-3">
                <div class="card-body p-4 p-md-5">
                    <h1 class="fs-4 fw-bold text-center mb-4">Connexion</h1>

                    <form action="{{ route('login') }}" method="POST">
                        @csrf

                        <div class="mb-3">
                            <label class="form-label fw-medium">Adresse e-mail</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-envelope"></i></span>
                                <input type="email" name="email"
                                       class="form-control @error('email') is-invalid @enderror"
                                       value="{{ old('email') }}" placeholder="vous@exemple.ma" autofocus>
                                @error('email')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="mb-3">
                            <label class="form-label fw-medium">Mot de passe</label>
                            <div class="input-group">
                                <span class="input-group-text"><i class="bi bi-lock"></i></span>
                                <input type="password" name="password"
                                       class="form-control @error('password') is-invalid @enderror"
                                       placeholder="••••••••">
                                @error('password')
                                    <div class="invalid-feedback">{{ $message }}</div>
                                @enderror
                            </div>
                        </div>

                        <div class="form-check mb-4">
                            <input type="checkbox" name="remember" id="remember" class="form-check-input">
                            <label class="form-check-label" for="remember">Se souvenir de moi</label>
                        </div>

                        <button type="submit" class="btn btn-primary w-100 fw-semibold py-2">
                            Se connecter
                        </button>
                    </form>

                    <hr class="my-4">

                    <p class="text-center small mb-0">
                        Pas encore de compte ?
                        <a href="{{ route('register') }}" class="fw-semibold text-primary text-decoration-none">
                            Créer un compte
                        </a>
                    </p>
                </div>
            </div>

            {{-- Demo credentials --}}
            <div class="alert alert-info small mt-3">
                <i class="bi bi-info-circle me-1"></i>
                <strong>Compte démo :</strong> admin@demo.ma / password
            </div>
        </div>
    </div>
</div>
@endsection
