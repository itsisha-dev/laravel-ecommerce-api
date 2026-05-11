<?php

namespace App\Providers;

use Illuminate\Foundation\Support\Providers\AuthServiceProvider as ServiceProvider;
use Illuminate\Support\Facades\Gate;

class AuthServiceProvider extends ServiceProvider
{
    protected $policies = [
        \App\Models\Vendor::class => \App\Policies\VendorPolicy::class,
        \App\Models\Category::class => \App\Policies\CategoryPolicy::class,
        \App\Models\Product::class => \App\Policies\ProductPolicy::class,
    ];

    /**
     * Register services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap services.
     */
    public function boot(): void
    {
        $this->registerPolicies();

        Gate::define('view-vendors', function ($user) {
            return $user->isAdmin();
        });

        Gate::define('manage-vendor', function ($user, $vendor) {
            return $user->id === $vendor->user_id || $user->isAdmin();
        });
    }
}
