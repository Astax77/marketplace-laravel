<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class City extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'region', 'is_active'];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function announcements(): HasMany
    {
        return $this->hasMany(Announcement::class);
    }

    public function users(): HasMany
    {
        return $this->hasMany(User::class);
    }
}
