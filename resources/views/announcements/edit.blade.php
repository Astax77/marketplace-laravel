@extends('layouts.app')

@section('title', 'Modifier l\'annonce – ' . $announcement->title)

@section('content')
<div class="container py-4" style="max-width:760px">

    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            <li class="breadcrumb-item"><a href="{{ route('profile.announcements') }}">Mes annonces</a></li>
            <li class="breadcrumb-item active">Modifier</li>
        </ol>
    </nav>

    <h1 class="fs-4 fw-bold mb-4">
        <i class="bi bi-pencil-square text-primary me-2"></i>Modifier l'annonce
    </h1>

    <form action="{{ route('announcements.update', $announcement->slug) }}" method="POST" enctype="multipart/form-data">
        @csrf @method('PUT')
        @include('announcements._form', ['announcement' => $announcement])
        <div class="d-flex gap-2 mt-4">
            <button type="submit" class="btn btn-primary fw-semibold px-4">
                <i class="bi bi-save me-2"></i>Enregistrer les modifications
            </button>
            <a href="{{ route('announcements.show', $announcement->slug) }}" class="btn btn-outline-secondary">Annuler</a>
        </div>
    </form>
</div>
@endsection
