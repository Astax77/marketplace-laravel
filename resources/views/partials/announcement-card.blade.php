{{-- partials/announcement-card.blade.php --}}
<div class="col-6 col-md-4 col-lg-3">
    <a href="{{ route('announcements.show', $announcement->slug) }}"
       class="card card-hover text-decoration-none h-100 border-0 shadow-sm rounded-3 overflow-hidden">
        {{-- Image --}}
        <div class="position-relative">
            <img src="{{ $announcement->main_image }}" alt="{{ $announcement->title }}"
                 class="card-img-top" style="height:160px;object-fit:cover;">
            {{-- Status badge --}}
            @if($announcement->status !== 'active')
                <span class="position-absolute top-0 end-0 m-2 badge bg-{{ $announcement->status_color }}">
                    {{ $announcement->status_label }}
                </span>
            @endif
            {{-- Negotiable badge --}}
            @if($announcement->is_negotiable)
                <span class="position-absolute bottom-0 start-0 m-2 badge bg-info text-dark small">
                    Négociable
                </span>
            @endif
        </div>

        <div class="card-body p-2">
            <p class="card-title small fw-semibold text-dark mb-1 text-truncate" title="{{ $announcement->title }}">
                {{ $announcement->title }}
            </p>
            <p class="fw-bold text-primary mb-1">{{ $announcement->formatted_price }}</p>
            <div class="d-flex justify-content-between align-items-center">
                <small class="text-muted">
                    <i class="bi bi-geo-alt-fill me-1"></i>{{ $announcement->city->name ?? '–' }}
                </small>
                <small class="text-muted">
                    <i class="bi bi-eye me-1"></i>{{ $announcement->views_count }}
                </small>
            </div>
            <small class="text-muted d-block mt-1">
                <i class="bi bi-clock me-1"></i>{{ $announcement->created_at->diffForHumans() }}
            </small>
        </div>
    </a>
</div>
