@extends('layouts.app')

@section('title', 'Mes annonces')

@section('content')
<div class="container py-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h1 class="fs-4 fw-bold mb-0">
            <i class="bi bi-grid text-primary me-2"></i>Mes annonces
        </h1>
        <a href="{{ route('announcements.create') }}" class="btn btn-primary btn-sm fw-semibold">
            <i class="bi bi-plus-circle me-1"></i>Nouvelle annonce
        </a>
    </div>

    @if($announcements->isEmpty())
        <div class="text-center py-5 text-muted">
            <i class="bi bi-inbox display-4 d-block mb-3"></i>
            <p class="fs-5">Vous n'avez pas encore d'annonces.</p>
            <a href="{{ route('announcements.create') }}" class="btn btn-primary">
                <i class="bi bi-plus-circle me-1"></i>Créer ma première annonce
            </a>
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Annonce</th>
                            <th>Catégorie</th>
                            <th>Prix</th>
                            <th>Statut</th>
                            <th>Vues</th>
                            <th>Date</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($announcements as $ann)
                        <tr>
                            <td style="max-width:250px">
                                <a href="{{ route('announcements.show', $ann->slug) }}"
                                   class="text-decoration-none fw-medium text-dark text-truncate d-block"
                                   title="{{ $ann->title }}">
                                    {{ $ann->title }}
                                </a>
                                <small class="text-muted">
                                    <i class="bi bi-geo-alt me-1"></i>{{ $ann->city->name ?? '–' }}
                                </small>
                            </td>
                            <td><small>{{ $ann->category->name ?? '–' }}</small></td>
                            <td class="fw-semibold text-primary text-nowrap">{{ $ann->formatted_price }}</td>
                            <td>
                                <span class="badge bg-{{ $ann->status_color }}">{{ $ann->status_label }}</span>
                            </td>
                            <td>
                                <small><i class="bi bi-eye me-1 text-muted"></i>{{ $ann->views_count }}</small>
                            </td>
                            <td><small class="text-muted">{{ $ann->created_at->format('d/m/Y') }}</small></td>
                            <td>
                                <div class="d-flex gap-1">
                                    <a href="{{ route('announcements.edit', $ann->slug) }}"
                                       class="btn btn-outline-primary btn-sm" title="Modifier">
                                        <i class="bi bi-pencil"></i>
                                    </a>

                                    {{-- Toggle status --}}
                                    <form action="{{ route('announcements.status', $ann->slug) }}" method="POST">
                                        @csrf @method('PATCH')
                                        @if($ann->status === 'active')
                                            <input type="hidden" name="status" value="sold">
                                            <button class="btn btn-outline-secondary btn-sm" title="Marquer vendu">
                                                <i class="bi bi-check2-circle"></i>
                                            </button>
                                        @elseif($ann->status === 'sold')
                                            <input type="hidden" name="status" value="active">
                                            <button class="btn btn-outline-success btn-sm" title="Réactiver">
                                                <i class="bi bi-arrow-counterclockwise"></i>
                                            </button>
                                        @endif
                                    </form>

                                    {{-- Delete --}}
                                    <form action="{{ route('announcements.destroy', $ann->slug) }}" method="POST"
                                          onsubmit="return confirm('Supprimer cette annonce ?')">
                                        @csrf @method('DELETE')
                                        <button class="btn btn-outline-danger btn-sm" title="Supprimer">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $announcements->links() }}
        </div>
    @endif
</div>
@endsection
