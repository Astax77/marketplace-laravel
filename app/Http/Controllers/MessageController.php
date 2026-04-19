<?php

namespace App\Http\Controllers;

use App\Http\Requests\SendMessageRequest;
use App\Models\Announcement;
use App\Models\Conversation;
use App\Services\MessageService;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class MessageController extends Controller
{
    public function __construct(private readonly MessageService $messageService) {}

    /**
     * List all conversations for the authenticated user.
     */
    public function index(): View
    {
        $conversations = $this->messageService->getUserConversations(auth()->user());

        return view('messages.index', compact('conversations'));
    }

    /**
     * Show a single conversation.
     */
    public function show(Conversation $conversation): View
    {
        $this->authorizeConversation($conversation);

        $this->messageService->markAsRead($conversation, auth()->user());

        $conversation->load(['announcement', 'buyer', 'seller', 'messages.sender']);

        return view('messages.show', compact('conversation'));
    }

    /**
     * Start a new conversation (contact seller from announcement page).
     */
    public function store(SendMessageRequest $request): RedirectResponse
    {
        $announcement = Announcement::findOrFail($request->input('announcement_id'));

        $conversation = $this->messageService->startConversation(
            buyer: auth()->user(),
            announcement: $announcement,
            body: $request->input('body'),
        );

        return redirect()
            ->route('messages.show', $conversation)
            ->with('success', 'Message envoyé !');
    }

    /**
     * Reply to an existing conversation.
     */
    public function reply(SendMessageRequest $request, Conversation $conversation): RedirectResponse
    {
        $this->authorizeConversation($conversation);

        $this->messageService->reply($conversation, auth()->user(), $request->input('body'));

        return redirect()
            ->route('messages.show', $conversation)
            ->with('success', 'Réponse envoyée.');
    }

    /**
     * Delete a conversation (soft removal from user's inbox).
     */
    public function destroy(Conversation $conversation): RedirectResponse
    {
        $this->authorizeConversation($conversation);
        $conversation->delete();

        return redirect()
            ->route('messages.index')
            ->with('success', 'Conversation supprimée.');
    }

    // ─── Private helpers ─────────────────────────────────────────────────────

    private function authorizeConversation(Conversation $conversation): void
    {
        $userId = auth()->id();
        abort_unless(
            $conversation->buyer_id === $userId || $conversation->seller_id === $userId,
            403,
            'Accès non autorisé.'
        );
    }
}
