<?php

use App\Http\Controllers\Auth\LoginController;
use App\Http\Controllers\Auth\RegisterController;
use App\Http\Controllers\Auth\ProfileController;
use App\Http\Controllers\AnnouncementController;
use App\Http\Controllers\MessageController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\HomeController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ─── Public routes ────────────────────────────────────────────────────────────
Route::get('/', [HomeController::class, 'index'])->name('home');

// Search
Route::get('/search', [SearchController::class, 'index'])->name('search');
Route::get('/search/suggestions', [SearchController::class, 'suggestions'])->name('search.suggestions');

// Announcements (public read)
Route::get('/annonces', [AnnouncementController::class, 'index'])->name('announcements.index');
Route::get('/annonces/{announcement:slug}', [AnnouncementController::class, 'show'])->name('announcements.show');
Route::get('/categories/{category:slug}', [AnnouncementController::class, 'byCategory'])->name('categories.show');

// ─── Guest-only routes ────────────────────────────────────────────────────────
Route::middleware('guest')->group(function () {
    Route::get('/inscription', [RegisterController::class, 'showRegistrationForm'])->name('register');
    Route::post('/inscription', [RegisterController::class, 'register']);

    Route::get('/connexion', [LoginController::class, 'showLoginForm'])->name('login');
    Route::post('/connexion', [LoginController::class, 'login']);
});

// ─── Authenticated routes ─────────────────────────────────────────────────────
Route::middleware('auth')->group(function () {

    // Auth
    Route::post('/deconnexion', [LoginController::class, 'logout'])->name('logout');

    // Profile
    Route::get('/profil', [ProfileController::class, 'show'])->name('profile.show');
    Route::get('/profil/modifier', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::put('/profil', [ProfileController::class, 'update'])->name('profile.update');
    Route::put('/profil/mot-de-passe', [ProfileController::class, 'updatePassword'])->name('profile.password');
    Route::get('/profil/annonces', [ProfileController::class, 'myAnnouncements'])->name('profile.announcements');

    // Announcements (authenticated CRUD)
    Route::get('/annonces/creer', [AnnouncementController::class, 'create'])->name('announcements.create');
    Route::post('/annonces', [AnnouncementController::class, 'store'])->name('announcements.store');
    Route::get('/annonces/{announcement:slug}/modifier', [AnnouncementController::class, 'edit'])
        ->middleware('can:update,announcement')
        ->name('announcements.edit');
    Route::put('/annonces/{announcement:slug}', [AnnouncementController::class, 'update'])
        ->middleware('can:update,announcement')
        ->name('announcements.update');
    Route::delete('/annonces/{announcement:slug}', [AnnouncementController::class, 'destroy'])
        ->middleware('can:delete,announcement')
        ->name('announcements.destroy');
    Route::patch('/annonces/{announcement:slug}/statut', [AnnouncementController::class, 'updateStatus'])
        ->middleware('can:update,announcement')
        ->name('announcements.status');

    // Messaging
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{conversation}', [MessageController::class, 'show'])->name('messages.show');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/{conversation}/repondre', [MessageController::class, 'reply'])->name('messages.reply');
    Route::delete('/messages/{conversation}', [MessageController::class, 'destroy'])->name('messages.destroy');
});
