<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Services\AnnouncementService;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function __construct(private readonly AnnouncementService $announcementService) {}

    public function show(): View
    {
        $user = auth()->user()->load('city');
        return view('profile.show', compact('user'));
    }

    public function edit(): View
    {
        $user   = auth()->user();
        $cities = City::where('is_active', true)->orderBy('name')->get();
        return view('profile.edit', compact('user', 'cities'));
    }

    public function update(Request $request): RedirectResponse
    {
        $user = auth()->user();

        $validated = $request->validate([
            'name'    => 'required|string|max:100',
            'phone'   => 'nullable|string|max:20',
            'city_id' => 'nullable|exists:cities,id',
            'bio'     => 'nullable|string|max:500',
            'avatar'  => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
        ]);

        if ($request->hasFile('avatar') && $request->file('avatar')->isValid()) {
            // Delete old avatar file
            if ($user->avatar) {
                $oldPath = public_path('uploads/' . $user->avatar);
                if (file_exists($oldPath)) {
                    unlink($oldPath);
                }
            }

            // Save new avatar directly in public/uploads/avatars/
            $avatarDir = public_path('uploads/avatars');
            if (! is_dir($avatarDir)) {
                mkdir($avatarDir, 0755, true);
            }

            $file     = $request->file('avatar');
            $filename = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
            $file->move($avatarDir, $filename);
            $validated['avatar'] = 'avatars/' . $filename;
        } else {
            unset($validated['avatar']);
        }

        $user->update($validated);

        return redirect()->route('profile.show')->with('success', 'Profil mis à jour avec succès.');
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $request->validate([
            'current_password' => 'required|string|current_password',
            'password'         => 'required|string|min:8|confirmed',
        ]);

        auth()->user()->update([
            'password' => Hash::make($request->input('password')),
        ]);

        return back()->with('success', 'Mot de passe modifié avec succès.');
    }

    public function myAnnouncements(): View
    {
        $announcements = $this->announcementService->getUserAnnouncements(auth()->user());
        return view('profile.announcements', compact('announcements'));
    }
}
