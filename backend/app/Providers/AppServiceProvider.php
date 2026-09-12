<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        Schema::defaultStringLength(191);

        if (env('APP_ENV') === 'production') {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        }

        \Illuminate\Support\Facades\View::composer('customer.partials.header', function ($view) {
            $view->with('navCategories', \App\Models\Category::where('show_in_nav', true)
                ->where('status', true)
                ->orderBy('sort_order')
                ->get());

            // Inject cart count
            $cartHelper = app(\App\Helpers\CartHelper::class);
            $view->with('cartCount', $cartHelper->getCartCount());
        });
    }
}
