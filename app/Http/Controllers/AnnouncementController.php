<?php

namespace App\Http\Controllers;

use App\Http\Requests\StoreAnnouncementRequest;
use App\Http\Requests\UpdateAnnouncementRequest;
use App\Models\Announcement;
use App\Models\Category;
use App\Models\City;
use App\Services\AnnouncementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class AnnouncementController extends Controller
{
    public function __construct(private readonly AnnouncementService $announcementService) {}

    /**
     * List all active announcements.
     */
    public function index(): View
    {
        $announcements = Announcement::with(['user', 'category', 'city'])
            ->active()
            ->latest()
            ->paginate(15);

        return view('announcements.index', compact('announcements'));
    }

    /**
     * Show a single announcement.
     */
    public function show(Announcement $announcement): View
    {
        abort_if($announcement->status === Announcement::STATUS_SUSPENDED && auth()->id() !== $announcement->user_id, 404);

        $this->announcementService->incrementViews($announcement);

        $similar = Announcement::with(['city'])
            ->active()
            ->where('category_id', $announcement->category_id)
            ->where('id', '!=', $announcement->id)
            ->take(4)
            ->get();

        $announcement->load(['user', 'category', 'city']);

        return view('announcements.show', compact('announcement', 'similar'));
    }

    /**
     * Show form to create a new announcement.
     */
    public function create(): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $cities     = City::where('is_active', true)->orderBy('name')->get();
        $conditions = Announcement::CONDITIONS;

        return view('announcements.create', compact('categories', 'cities', 'conditions'));
    }

    /**
     * Store a new announcement.
     */
    public function store(StoreAnnouncementRequest $request): RedirectResponse
    {
        $announcement = $this->announcementService->create(
            user: auth()->user(),
            data: $request->validated(),
            images: $request->file('images', []),
        );

        return redirect()
            ->route('announcements.show', $announcement)
            ->with('success', 'Annonce publiée avec succès !');
    }

    /**
     * Show form to edit an announcement.
     */
    public function edit(Announcement $announcement): View
    {
        $categories = Category::where('is_active', true)->orderBy('name')->get();
        $cities     = City::where('is_active', true)->orderBy('name')->get();
        $conditions = Announcement::CONDITIONS;

        return view('announcements.edit', compact('announcement', 'categories', 'cities', 'conditions'));
    }

    /**
     * Update an announcement.
     */
    public function update(UpdateAnnouncementRequest $request, Announcement $announcement): RedirectResponse
    {
        $this->announcementService->update(
            announcement: $announcement,
            data: $request->validated(),
            newImages: $request->file('images', []),
        );

        return redirect()
            ->route('announcements.show', $announcement)
            ->with('success', 'Annonce mise à jour avec succès !');
    }

    /**
     * Delete an announcement.
     */
    public function destroy(Announcement $announcement): RedirectResponse
    {
        $this->announcementService->delete($announcement);

        return redirect()
            ->route('profile.announcements')
            ->with('success', 'Annonce supprimée avec succès.');
    }

    /**
     * Update the status of an announcement.
     */
    public function updateStatus(Request $request, Announcement $announcement): RedirectResponse
    {
        $request->validate(['status' => 'required|in:' . implode(',', Announcement::STATUSES)]);

        $this->announcementService->changeStatus($announcement, $request->input('status'));

        return back()->with('success', 'Statut mis à jour.');
    }

    /**
     * List announcements filtered by category.
     */
    public function byCategory(Category $category): View
    {
        $childIds = $category->children()->pluck('id');
        $allIds   = $childIds->prepend($category->id);

        $announcements = Announcement::with(['user', 'city'])
            ->active()
            ->whereIn('category_id', $allIds)
            ->latest()
            ->paginate(15);

        return view('announcements.index', compact('announcements', 'category'));
    }
}
