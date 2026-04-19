<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        'name',
        'email',
        'password',
        'phone',
        'city_id',
        'avatar',
        'bio',
        'is_active',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password'          => 'hashed',
            'is_active'         => 'boolean',
        ];
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function city()
    {
        return $this->belongsTo(City::class);
    }

    public function sentMessages(): HasMany
    {
        return $this->hasMany(Message::class, 'sender_id');
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getAvatarUrlAttribute(): string
    {
        if ($this->avatar) {
            // Files now in public/uploads/ — directly accessible without symlink
            $fullPath = public_path('uploads/' . $this->avatar);
            if (file_exists($fullPath)) {
                return asset('uploads/' . $this->avatar);
            }
        }

        // Auto-generated avatar from name — always works
        $name = urlencode($this->name ?? 'User');
        return "https://ui-avatars.com/api/?name={$name}&background=0d6efd&color=fff&size=100&rounded=true";
    }

    public function getActiveAnnouncementsCountAttribute(): int
    {
        return $this->announcements()->where('status', 'active')->count();
    }
}
