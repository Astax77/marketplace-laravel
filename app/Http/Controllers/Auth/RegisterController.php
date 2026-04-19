<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\City;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\View\View;

class RegisterController extends Controller
{
    public function showRegistrationForm(): View
    {
        $cities = City::where('is_active', true)->orderBy('name')->get();
        return view('auth.register', compact('cities'));
    }

    public function register(Request $request): RedirectResponse
    {
        $validated = $request->validate([
            'name'      => 'required|string|max:100',
            'email'     => 'required|email|unique:users,email|max:150',
            'phone'     => 'nullable|string|max:20',
            'city_id'   => 'nullable|exists:cities,id',
            'password'  => 'required|string|min:8|confirmed',
        ]);

        $user = User::create([
            'name'     => $validated['name'],
            'email'    => $validated['email'],
            'phone'    => $validated['phone'] ?? null,
            'city_id'  => $validated['city_id'] ?? null,
            'password' => Hash::make($validated['password']),
        ]);

        Auth::login($user);

        return redirect()->route('home')->with('success', "Bienvenue, {$user->name} !");
    }
}
