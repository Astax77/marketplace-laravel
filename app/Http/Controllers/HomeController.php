<?php

namespace App\Http\Controllers;

use App\Models\Announcement;
use App\Models\Category;
use App\Models\City;
use Illuminate\View\View;

class HomeController extends Controller
{
    public function index(): View
    {
        // Latest active announcements
        $latestAnnouncements = Announcement::with(['user', 'category', 'city'])
            ->where('status', 'active')
            ->latest()
            ->take(12)
            ->get();

        // Parent categories with child count and active announcements count
        $categories = Category::with('children')
            ->whereNull('parent_id')
            ->where('is_active', true)
            ->withCount([
                'announcements' => fn ($q) => $q->where('status', 'active'),
            ])
            ->orderBy('name')
            ->get();

        // Popular cities - safe query without having() on empty tables
        $popularCities = City::withCount([
                'announcements' => fn ($q) => $q->where('status', 'active'),
            ])
            ->orderByDesc('announcements_count')
            ->take(8)
            ->get()
            ->filter(fn ($city) => $city->announcements_count > 0);

        return view('home', compact('latestAnnouncements', 'categories', 'popularCities'));
    }
}
