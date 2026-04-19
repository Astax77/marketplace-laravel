@extends('layouts.app')

@section('title', 'Messagerie')

@section('content')
<div class="container py-4" style="max-width:820px">
    <h1 class="fs-4 fw-bold mb-4">
        <i class="bi bi-chat-dots text-primary me-2"></i>Messagerie
    </h1>

    @if($conversations->isEmpty())
        <div class="card border-0 shadow-sm rounded-3 p-5 text-center text-muted">
            <i class="bi bi-chat-square-dots display-4 d-block mb-3"></i>
            <p class="fs-5">Aucune conversation pour le moment.</p>
            <p class="small">Contactez un vendeur depuis une annonce pour démarrer une conversation.</p>
            <a href="{{ route('announcements.index') }}" class="btn btn-primary d-inline-block mx-auto mt-2" style="width:fit-content">
                Voir les annonces
            </a>
        </div>
    @else
        <div class="card border-0 shadow-sm rounded-3 overflow-hidden">
            @foreach($conversations as $conv)
                @php
                    $other   = $conv->getOtherParticipant(auth()->id());
                    $unread  = $conv->getUnreadCountFor(auth()->id());
                    $lastMsg = $conv->lastMessage;
                @endphp
                <a href="{{ route('messages.show', $conv->id) }}"
                   class="d-flex align-items-start gap-3 p-3 border-bottom text-decoration-none text-dark
                          {{ $unread > 0 ? 'bg-blue-subtle fw-semibold' : '' }}
                          conversation-item">
                    <img src="{{ $other->avatar_url }}" alt="Avatar"
                         class="rounded-circle flex-shrink-0" width="46" height="46" style="object-fit:cover">

                    <div class="flex-grow-1 overflow-hidden">
                        <div class="d-flex justify-content-between align-items-center">
                            <span class="fw-semibold">{{ $other->name }}</span>
                            <small class="text-muted">
                                {{ $conv->last_message_at?->diffForHumans() }}
                            </small>
                        </div>
                        <div class="text-muted small text-truncate">
                            <i class="bi bi-tag me-1 text-primary"></i>{{ $conv->announcement?->title ?? 'Annonce supprimée' }}
                        </div>
                        @if($lastMsg)
                            <div class="small text-truncate {{ $unread > 0 ? 'text-dark fw-medium' : 'text-muted' }}">
                                @if($lastMsg->sender_id === auth()->id())
                                    <span class="text-muted">Vous: </span>
                                @endif
                                {{ $lastMsg->body }}
                            </div>
                        @endif
                    </div>

                    @if($unread > 0)
                        <span class="badge bg-primary rounded-pill align-self-center flex-shrink-0">{{ $unread }}</span>
                    @endif
                </a>
            @endforeach
        </div>

        <div class="mt-4 d-flex justify-content-center">
            {{ $conversations->links() }}
        </div>
    @endif
</div>
@endsection
