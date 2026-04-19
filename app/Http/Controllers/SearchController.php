<?php

namespace App\Http\Controllers;

use App\Models\Category;
use App\Models\City;
use App\Services\SearchService;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class SearchController extends Controller
{
    public function __construct(private readonly SearchService $searchService) {}

    /**
     * Execute a multi-criteria search.
     */
    public function index(Request $request): View
    {
        $filters = $request->only([
            'q', 'category_id', 'city_id', 'price_min', 'price_max', 'condition', 'sort',
        ]);

        $results    = $this->searchService->search($filters);
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $cities     = City::where('is_active', true)->orderBy('name')->get();

        return view('search.results', compact('results', 'filters', 'categories', 'cities'));
    }

    /**
     * Return JSON auto-complete suggestions.
     */
    public function suggestions(Request $request): JsonResponse
    {
        $query = $request->input('q', '');

        if (strlen($query) < 2) {
            return response()->json([]);
        }

        $suggestions = \App\Models\Announcement::active()
            ->where('title', 'LIKE', "%{$query}%")
            ->select('id', 'title', 'slug')
            ->take(8)
            ->get()
            ->map(fn ($a) => [
                'label' => $a->title,
                'url'   => route('announcements.show', $a->slug),
            ]);

        return response()->json($suggestions);
    }
}
