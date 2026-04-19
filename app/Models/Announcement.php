<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

class Announcement extends Model
{
    use HasFactory;

    public const STATUS_ACTIVE    = 'active';
    public const STATUS_SOLD      = 'sold';
    public const STATUS_SUSPENDED = 'suspended';

    public const STATUSES = [
        self::STATUS_ACTIVE,
        self::STATUS_SOLD,
        self::STATUS_SUSPENDED,
    ];

    public const CONDITIONS = ['new', 'like_new', 'good', 'fair', 'poor'];

    protected $fillable = [
        'user_id',
        'category_id',
        'city_id',
        'title',
        'slug',
        'description',
        'price',
        'condition',
        'status',
        'images',
        'views_count',
        'is_negotiable',
    ];

    protected function casts(): array
    {
        return [
            'images'        => 'array',
            'price'         => 'decimal:2',
            'is_negotiable' => 'boolean',
            'views_count'   => 'integer',
        ];
    }

    // ─── Boot ─────────────────────────────────────────────────────────────────

    protected static function boot(): void
    {
        parent::boot();

        static::creating(function (Announcement $announcement) {
            if (empty($announcement->slug)) {
                $announcement->slug = Str::slug($announcement->title) . '-' . Str::random(6);
            }
        });
    }

    // ─── Relationships ────────────────────────────────────────────────────────

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function conversations(): HasMany
    {
        return $this->hasMany(Conversation::class);
    }

    // ─── Scopes ───────────────────────────────────────────────────────────────

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('status', self::STATUS_ACTIVE);
    }

    public function scopeByCategory(Builder $query, int $categoryId): Builder
    {
        return $query->where('category_id', $categoryId);
    }

    public function scopeByCity(Builder $query, int $cityId): Builder
    {
        return $query->where('city_id', $cityId);
    }

    public function scopePriceBetween(Builder $query, ?float $min, ?float $max): Builder
    {
        if ($min !== null) {
            $query->where('price', '>=', $min);
        }
        if ($max !== null) {
            $query->where('price', '<=', $max);
        }
        return $query;
    }

    public function scopeSearch(Builder $query, string $keyword): Builder
    {
        return $query->where(function (Builder $q) use ($keyword) {
            $q->where('title', 'LIKE', "%{$keyword}%")
              ->orWhere('description', 'LIKE', "%{$keyword}%");
        });
    }

    // ─── Helpers ──────────────────────────────────────────────────────────────

    /**
     * Resolve a single image path to a full URL.
     * Handles: full https:// URLs, and local public/uploads/ paths.
     */
    private function resolveImageUrl(string $path): string
    {
        // Already a full URL (http/https) — use as-is
        if (str_starts_with($path, 'http://') || str_starts_with($path, 'https://')) {
            return $path;
        }

        // Local file in public/uploads/
        return asset('uploads/' . $path);
    }

    // ─── Accessors ────────────────────────────────────────────────────────────

    /**
     * Get the main (first) image URL.
     * Returns a placeholder if no images are set.
     */
    public function getMainImageAttribute(): string
    {
        $images = $this->images;

        if (! empty($images) && is_array($images)) {
            return $this->resolveImageUrl($images[0]);
        }

        return 'https://placehold.co/400x300/e9ecef/6c757d?text=Pas+d+image';
    }

    /**
     * Get all image URLs as an array (used in show.blade.php carousel).
     */
    public function getImageUrlsAttribute(): array
    {
        $images = $this->images;

        if (empty($images) || ! is_array($images)) {
            return [];
        }

        return array_map(fn ($path) => $this->resolveImageUrl($path), $images);
    }

    public function getFormattedPriceAttribute(): string
    {
        return number_format((float) $this->price, 0, ',', ' ') . ' MAD';
    }

    public function getStatusLabelAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE    => 'Actif',
            self::STATUS_SOLD      => 'Vendu',
            self::STATUS_SUSPENDED => 'Suspendu',
            default                => 'Inconnu',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match ($this->status) {
            self::STATUS_ACTIVE    => 'success',
            self::STATUS_SOLD      => 'secondary',
            self::STATUS_SUSPENDED => 'warning',
            default                => 'dark',
        };
    }

    public function getConditionLabelAttribute(): string
    {
        return match ($this->condition) {
            'new'      => 'Neuf',
            'like_new' => 'Comme neuf',
            'good'     => 'Bon état',
            'fair'     => 'État correct',
            'poor'     => 'À rénover',
            default    => '-',
        };
    }
}
