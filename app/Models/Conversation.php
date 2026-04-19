<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Conversation extends Model
{
    use HasFactory;

    protected $fillable = [
        'announcement_id',
        'buyer_id',
        'seller_id',
        'last_message_at',
    ];

    protected function casts(): array
    {
        return ['last_message_at' => 'datetime'];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function announcement(): BelongsTo
    {
        return $this->belongsTo(Announcement::class);
    }

    public function buyer(): BelongsTo
    {
        return $this->belongsTo(User::class, 'buyer_id');
    }

    public function seller(): BelongsTo
    {
        return $this->belongsTo(User::class, 'seller_id');
    }

    public function messages(): HasMany
    {
        return $this->hasMany(Message::class)->orderBy('created_at');
    }

    // ─── Helpers ─────────────────────────────────────────────────────────────

    public function getOtherParticipant(int $userId): User
    {
        return $this->buyer_id === $userId ? $this->seller : $this->buyer;
    }

    public function getUnreadCountFor(int $userId): int
    {
        return $this->messages()
            ->where('sender_id', '!=', $userId)
            ->where('is_read', false)
            ->count();
    }

    public function getLastMessageAttribute(): ?Message
    {
        return $this->messages()->latest()->first();
    }
}
