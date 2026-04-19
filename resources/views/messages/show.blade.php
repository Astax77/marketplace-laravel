@extends('layouts.app')

@section('title', 'Conversation – ' . $conversation->announcement?->title)

@section('content')
<div class="container py-4" style="max-width:760px">

    {{-- Header --}}
    <div class="card border-0 shadow-sm rounded-3 mb-3">
        <div class="card-body p-3 d-flex align-items-center gap-3">
            <a href="{{ route('messages.index') }}" class="btn btn-outline-secondary btn-sm">
                <i class="bi bi-arrow-left"></i>
            </a>
            @php $other = $conversation->getOtherParticipant(auth()->id()); @endphp
            <img src="{{ $other->avatar_url }}" alt="Avatar"
                 class="rounded-circle" width="40" height="40" style="object-fit:cover">
            <div class="flex-grow-1 overflow-hidden">
                <div class="fw-semibold">{{ $other->name }}</div>
                <a href="{{ route('announcements.show', $conversation->announcement?->slug) }}"
                   class="small text-muted text-truncate text-decoration-none">
                    <i class="bi bi-tag me-1 text-primary"></i>
                    {{ $conversation->announcement?->title ?? 'Annonce supprimée' }}
                </a>
            </div>
            @if($conversation->announcement)
                <span class="fw-bold text-primary text-nowrap">
                    {{ $conversation->announcement->formatted_price }}
                </span>
            @endif
        </div>
    </div>

    {{-- Messages thread --}}
    <div class="card border-0 shadow-sm rounded-3 mb-3">
        <div class="card-body p-3" style="max-height:460px; overflow-y:auto;" id="messagesContainer">
            @foreach($conversation->messages as $msg)
                @php $isMine = $msg->sender_id === auth()->id(); @endphp
                <div class="d-flex {{ $isMine ? 'justify-content-end' : 'justify-content-start' }} mb-3">
                    @if(!$isMine)
                        <img src="{{ $msg->sender->avatar_url }}" alt="Avatar"
                             class="rounded-circle me-2 align-self-end flex-shrink-0"
                             width="32" height="32" style="object-fit:cover">
                    @endif
                    <div style="max-width:70%">
                        <div class="rounded-3 px-3 py-2 {{ $isMine ? 'bg-primary text-white' : 'bg-light text-dark' }}">
                            {{ $msg->body }}
                        </div>
                        <div class="text-muted mt-1 {{ $isMine ? 'text-end' : '' }}" style="font-size:0.72rem">
                            {{ $msg->created_at->format('d/m H:i') }}
                            @if($isMine)
                                <i class="bi {{ $msg->is_read ? 'bi-check2-all text-info' : 'bi-check2' }} ms-1"></i>
                            @endif
                        </div>
                    </div>
                    @if($isMine)
                        <img src="{{ auth()->user()->avatar_url }}" alt="Avatar"
                             class="rounded-circle ms-2 align-self-end flex-shrink-0"
                             width="32" height="32" style="object-fit:cover">
                    @endif
                </div>
            @endforeach
        </div>
    </div>

    {{-- Reply form --}}
    <div class="card border-0 shadow-sm rounded-3">
        <div class="card-body p-3">
            <form action="{{ route('messages.reply', $conversation->id) }}" method="POST">
                @csrf
                <div class="input-group">
                    <textarea name="body" rows="2"
                              class="form-control @error('body') is-invalid @enderror"
                              placeholder="Écrire un message…">{{ old('body') }}</textarea>
                    <button class="btn btn-primary px-3" type="submit" title="Envoyer">
                        <i class="bi bi-send-fill"></i>
                    </button>
                    @error('body') <div class="invalid-feedback">{{ $message }}</div> @enderror
                </div>
            </form>
        </div>
    </div>

    {{-- Delete conversation --}}
    <div class="text-end mt-2">
        <form action="{{ route('messages.destroy', $conversation->id) }}" method="POST"
              onsubmit="return confirm('Supprimer cette conversation ?')">
            @csrf @method('DELETE')
            <button class="btn btn-link btn-sm text-danger text-decoration-none">
                <i class="bi bi-trash me-1"></i>Supprimer la conversation
            </button>
        </form>
    </div>
</div>
@endsection

@push('scripts')
<script>
    // Auto-scroll to bottom
    const c = document.getElementById('messagesContainer');
    if (c) c.scrollTop = c.scrollHeight;

    // Ctrl+Enter to submit
    document.querySelector('textarea[name="body"]')?.addEventListener('keydown', function(e) {
        if (e.ctrlKey && e.key === 'Enter') this.closest('form').submit();
    });
</script>
@endpush
