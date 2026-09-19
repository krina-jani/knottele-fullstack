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

        if ($appUrl = config('app.url')) {
            \Illuminate\Support\Facades\URL::forceRootUrl($appUrl);
        }

        $isHttps = (isset($_SERVER['HTTPS']) && $_SERVER['HTTPS'] === 'on')
            || (isset($_SERVER['HTTP_X_FORWARDED_PROTO']) && $_SERVER['HTTP_X_FORWARDED_PROTO'] === 'https')
            || (request() && request()->isSecure());

        if ($isHttps) {
            \Illuminate\Support\Facades\URL::forceScheme('https');
        } elseif (isset($_SERVER['HTTP_HOST'])) {
            \Illuminate\Support\Facades\URL::forceScheme('http');
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
