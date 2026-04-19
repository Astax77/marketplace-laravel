@extends('layouts.app')

@section('title', 'Résultats de recherche – ' . (request('q') ?: 'Toutes les annonces'))

@section('content')
<div class="container py-4">
    <div class="row g-4">

        {{-- ── Filters sidebar ─────────────────────────────────────────────── --}}
        <div class="col-lg-3">
            <div class="card border-0 shadow-sm rounded-3 sticky-top" style="top:80px">
                <div class="card-body p-3">
                    <h5 class="fw-semibold mb-3">
                        <i class="bi bi-funnel text-primary me-2"></i>Filtres
                    </h5>
                    <form action="{{ route('search') }}" method="GET" id="filterForm">

                        {{-- Keyword --}}
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Mot-clé</label>
                            <input type="text" name="q" class="form-control form-control-sm"
                                   value="{{ $filters['q'] ?? '' }}" placeholder="Rechercher…">
                        </div>

                        {{-- Category --}}
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Catégorie</label>
                            <select name="category_id" class="form-select form-select-sm">
                                <option value="">Toutes</option>
                                @php $grouped = $categories->groupBy('parent_id'); @endphp
                                @foreach($grouped->get(null, collect()) as $parent)
                                    <optgroup label="{{ $parent->name }}">
                                        @foreach($grouped->get($parent->id, collect()) as $child)
                                            <option value="{{ $child->id }}"
                                                {{ ($filters['category_id'] ?? '') == $child->id ? 'selected' : '' }}>
                                                {{ $child->name }}
                                            </option>
                                        @endforeach
                                    </optgroup>
                                @endforeach
                            </select>
                        </div>

                        {{-- City --}}
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Ville</label>
                            <select name="city_id" class="form-select form-select-sm">
                                <option value="">Toutes les villes</option>
                                @foreach($cities as $city)
                                    <option value="{{ $city->id }}"
                                        {{ ($filters['city_id'] ?? '') == $city->id ? 'selected' : '' }}>
                                        {{ $city->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Price range --}}
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Prix (MAD)</label>
                            <div class="d-flex gap-2">
                                <input type="number" name="price_min" class="form-control form-control-sm"
                                       placeholder="Min" value="{{ $filters['price_min'] ?? '' }}" min="0">
                                <input type="number" name="price_max" class="form-control form-control-sm"
                                       placeholder="Max" value="{{ $filters['price_max'] ?? '' }}" min="0">
                            </div>
                        </div>

                        {{-- Condition --}}
                        <div class="mb-3">
                            <label class="form-label small fw-medium">État</label>
                            <select name="condition" class="form-select form-select-sm">
                                <option value="">Tous</option>
                                @foreach(['new' => 'Neuf', 'like_new' => 'Comme neuf', 'good' => 'Bon état', 'fair' => 'État correct', 'poor' => 'À rénover'] as $val => $label)
                                    <option value="{{ $val }}" {{ ($filters['condition'] ?? '') === $val ? 'selected' : '' }}>
                                        {{ $label }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Sort --}}
                        <div class="mb-3">
                            <label class="form-label small fw-medium">Trier par</label>
                            <select name="sort" class="form-select form-select-sm">
                                <option value="recent" {{ ($filters['sort'] ?? 'recent') === 'recent' ? 'selected' : '' }}>Plus récentes</option>
                                <option value="price_asc" {{ ($filters['sort'] ?? '') === 'price_asc' ? 'selected' : '' }}>Prix croissant</option>
                                <option value="price_desc" {{ ($filters['sort'] ?? '') === 'price_desc' ? 'selected' : '' }}>Prix décroissant</option>
                                <option value="views" {{ ($filters['sort'] ?? '') === 'views' ? 'selected' : '' }}>Plus vues</option>
                            </select>
                        </div>

                        <div class="d-grid gap-2">
                            <button type="submit" class="btn btn-primary btn-sm fw-semibold">
                                <i class="bi bi-search me-1"></i>Appliquer
                            </button>
                            <a href="{{ route('search') }}" class="btn btn-outline-secondary btn-sm">
                                <i class="bi bi-x-circle me-1"></i>Réinitialiser
                            </a>
                        </div>

                    </form>
                </div>
            </div>
        </div>

        {{-- ── Results ──────────────────────────────────────────────────────── --}}
        <div class="col-lg-9">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <div>
                    <h1 class="fs-5 fw-bold mb-0">
                        @if(!empty($filters['q']))
                            Résultats pour <span class="text-primary">« {{ $filters['q'] }} »</span>
                        @else
                            Toutes les annonces
                        @endif
                    </h1>
                    <small class="text-muted">{{ $results->total() }} annonce(s) trouvée(s)</small>
                </div>
            </div>

            @if($results->isEmpty())
                <div class="text-center py-5 text-muted">
                    <i class="bi bi-search display-4 d-block mb-3"></i>
                    <p class="fs-5">Aucune annonce ne correspond à votre recherche.</p>
                    <p class="small">Essayez des mots-clés différents ou élargissez les filtres.</p>
                </div>
            @else
                <div class="row g-3">
                    @foreach($results as $announcement)
                        @include('partials.announcement-card', ['announcement' => $announcement])
                    @endforeach
                </div>
                <div class="mt-4 d-flex justify-content-center">
                    {{ $results->links() }}
                </div>
            @endif
        </div>

    </div>
</div>
@endsection
