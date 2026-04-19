<?php

namespace App\Services;

use App\Models\Announcement;
use App\Models\Conversation;
use App\Models\Message;
use App\Models\User;
use Illuminate\Pagination\LengthAwarePaginator;

class MessageService
{
    /**
     * Start a new conversation or retrieve an existing one, then send the first message.
     */
    public function startConversation(User $buyer, Announcement $announcement, string $body): Conversation
    {
        if ($buyer->id === $announcement->user_id) {
            throw new \LogicException('Vous ne pouvez pas contacter votre propre annonce.');
        }

        // Find or create the conversation
        $conversation = Conversation::firstOrCreate(
            [
                'announcement_id' => $announcement->id,
                'buyer_id'        => $buyer->id,
                'seller_id'       => $announcement->user_id,
            ],
            ['last_message_at' => now()]
        );

        $this->sendMessage($conversation, $buyer, $body);

        return $conversation;
    }

    /**
     * Send a reply inside an existing conversation.
     */
    public function reply(Conversation $conversation, User $sender, string $body): Message
    {
        if (!$this->isParticipant($conversation, $sender)) {
            throw new \LogicException('Accès non autorisé à cette conversation.');
        }

        return $this->sendMessage($conversation, $sender, $body);
    }

    /**
     * Mark all messages sent by the other party as read.
     */
    public function markAsRead(Conversation $conversation, User $reader): void
    {
        $conversation->messages()
            ->where('sender_id', '!=', $reader->id)
            ->where('is_read', false)
            ->update(['is_read' => true]);
    }

    /**
     * Get all conversations for a user, ordered by last activity.
     */
    public function getUserConversations(User $user, int $perPage = 20): LengthAwarePaginator
    {
        return Conversation::with(['announcement', 'buyer', 'seller'])
            ->where('buyer_id', $user->id)
            ->orWhere('seller_id', $user->id)
            ->orderByDesc('last_message_at')
            ->paginate($perPage);
    }

    /**
     * Get total unread message count for a user.
     */
    public function getUnreadCount(User $user): int
    {
        return Message::whereHas('conversation', function ($q) use ($user) {
            $q->where('buyer_id', $user->id)
              ->orWhere('seller_id', $user->id);
        })
        ->where('sender_id', '!=', $user->id)
        ->where('is_read', false)
        ->count();
    }

    // ─── Private helpers ─────────────────────────────────────────────────────

    private function sendMessage(Conversation $conversation, User $sender, string $body): Message
    {
        $message = $conversation->messages()->create([
            'sender_id' => $sender->id,
            'body'      => $body,
            'is_read'   => false,
        ]);

        $conversation->update(['last_message_at' => now()]);

        return $message;
    }

    private function isParticipant(Conversation $conversation, User $user): bool
    {
        return $conversation->buyer_id === $user->id
            || $conversation->seller_id === $user->id;
    }
}
