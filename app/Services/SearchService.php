<?php

namespace App\Services;

use App\Models\Announcement;
use Illuminate\Pagination\LengthAwarePaginator;

class SearchService
{
    /**
     * Perform a multi-criteria search on announcements.
     */
    public function search(array $filters, int $perPage = 15): LengthAwarePaginator
    {
        $query = Announcement::query()
            ->with(['user', 'category', 'city'])
            ->active();

        // Keyword filter
        if (!empty($filters['q'])) {
            $query->search(trim($filters['q']));
        }

        // Category filter (supports hierarchical: parent includes children)
        if (!empty($filters['category_id'])) {
            $categoryIds = $this->resolveCategoryIds((int) $filters['category_id']);
            $query->whereIn('category_id', $categoryIds);
        }

        // City filter
        if (!empty($filters['city_id'])) {
            $query->byCity((int) $filters['city_id']);
        }

        // Price range
        if (isset($filters['price_min']) || isset($filters['price_max'])) {
            $query->priceBetween(
                isset($filters['price_min']) ? (float) $filters['price_min'] : null,
                isset($filters['price_max']) ? (float) $filters['price_max'] : null,
            );
        }

        // Condition filter
        if (!empty($filters['condition'])) {
            $query->where('condition', $filters['condition']);
        }

        // Sort
        $sort = $filters['sort'] ?? 'recent';
        match ($sort) {
            'price_asc'  => $query->orderBy('price', 'asc'),
            'price_desc' => $query->orderBy('price', 'desc'),
            'views'      => $query->orderBy('views_count', 'desc'),
            default      => $query->latest(),
        };

        return $query->paginate($perPage)->appends($filters);
    }

    /**
     * Return category ID + all children IDs for hierarchical search.
     *
     * @return int[]
     */
    private function resolveCategoryIds(int $categoryId): array
    {
        $ids = [$categoryId];

        $children = \App\Models\Category::where('parent_id', $categoryId)->pluck('id')->toArray();
        $ids      = array_merge($ids, $children);

        return $ids;
    }
}
