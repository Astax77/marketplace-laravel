<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Str;

class Category extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'slug', 'icon', 'parent_id', 'description', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    protected static function boot(): void
    {
        parent::boot();
        static::creating(function (Category $cat) {
            if (empty($cat->slug)) {
                $cat->slug = Str::slug($cat->name);
            }
        });
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function parent(): BelongsTo
    {
        return $this->belongsTo(Category::class, 'parent_id');
    }

    public function children(): HasMany
    {
        return $this->hasMany(Category::class, 'parent_id');
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    public function getIsParentAttribute(): bool
    {
        return is_null($this->parent_id);
    }

    public function getAnnouncementsCountAttribute(): int
    {
        return $this->announcements()->where('status', 'active')->count();
    }
}
