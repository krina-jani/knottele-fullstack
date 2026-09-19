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

        if (!app()->runningInConsole() && request()) {
            $host = request()->getHost();
            $scheme = request()->getScheme();

            // Auto-detect live VPS or /knottele subpath to ensure links never render as localhost:8000
            if ($host === '187.127.158.24' || request()->is('knottele*') || str_contains(request()->getRequestUri(), '/knottele')) {
                \Illuminate\Support\Facades\URL::forceRootUrl($scheme . '://' . $host . '/knottele');
            } else {
                \Illuminate\Support\Facades\URL::forceRootUrl($scheme . '://' . request()->getHttpHost());
            }
        } elseif ($appUrl = config('app.url')) {
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
