<?php

namespace App\Providers;

use App\Models\Announcement;
use App\Policies\AnnouncementPolicy;
use Illuminate\Support\Facades\Gate;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        // Services are resolved via constructor injection (no manual binding needed)
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        // Register policy
        Gate::policy(Announcement::class, AnnouncementPolicy::class);

        // Paginator uses Bootstrap 5 views
        \Illuminate\Pagination\Paginator::useBootstrapFive();
    }
}
