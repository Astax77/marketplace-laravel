@extends('layouts.app')

@section('title', 'Déposer une annonce')

@section('content')
<div class="container py-4" style="max-width:760px">

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('profile.announcements') }}">Mes annonces</a></li>
            <li class="breadcrumb-item active">Nouvelle annonce</li>
        </ol>
    </nav>

    <h1 class="fs-4 fw-bold mb-4">
        <i class="bi bi-plus-circle text-primary me-2"></i>Déposer une annonce
    </h1>

    <form action="{{ route('announcements.store') }}" method="POST" enctype="multipart/form-data">
        @csrf
        @include('announcements._form', ['announcement' => null])
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary fw-semibold px-4">
                <i class="bi bi-cloud-upload me-2"></i>Publier l'annonce
            </button>
            <a href="{{ route('profile.announcements') }}" class="btn btn-outline-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection
