@extends('layouts.app')

@section('title', isset($category) ? $category->name . ' – Annonces' : 'Toutes les annonces')

@section('content')
<div class="container py-4">

    {{-- Breadcrumb --}}
    <nav aria-label="breadcrumb" class="mb-3">
        <ol class="breadcrumb small">
            <li class="breadcrumb-item"><a href="{{ route('home') }}">Accueil</a></li>
            @isset($category)
                @if($category->parent)
                    <li class="breadcrumb-item">
                        <a href="{{ route('categories.show', $category->parent->slug) }}">{{ $category->parent->name }}</a>
                    </li>
                @endif
                <li class="breadcrumb-item active">{{ $category->name }}</li>
            @else
                <li class="breadcrumb-item active">Toutes les annonces</li>
            @endisset
        </ol>
    </nav>

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fs-4 fw-bold mb-0">
            @isset($category)
                <i class="bi {{ $category->icon ?? 'bi-tag' }} text-primary me-2"></i>{{ $category->name }}
            @else
                <i class="bi bi-grid text-primary me-2"></i>Toutes les annonces
            @endisset
        </h1>
        <span class="badge bg-light text-dark border">
            {{ $announcements->total() }} annonce(s)
        </span>
    </div>

    @if($announcements->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox display-4 d-block mb-3"></i>
            <p class="fs-5">Aucune annonce disponible pour le moment.</p>
            @auth
                <a href="{{ route('announcements.create') }}" class="btn btn-primary">
                    <i class="bi bi-plus-circle me-1"></i>Publiez la première !
                </a>
            @endauth
        </div>
    @else
        <div class="row g-3">
            @foreach($announcements as $announcement)
                @include('partials.announcement-card', ['announcement' => $announcement])
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $announcements->links() }}
        </div>
    @endif
</div>
@endsection
