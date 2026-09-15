<?php

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| ADMIN CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Admin\AuthController as AdminAuth;
use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\CategoryController as AdminCategory;
use App\Http\Controllers\Admin\BrandController as AdminBrand;
use App\Http\Controllers\Admin\ProductController as AdminProduct;
use App\Http\Controllers\Admin\OrderController as AdminOrder;
use App\Http\Controllers\Admin\MediaController as AdminMedia;
use App\Http\Controllers\Admin\TaxController as AdminTax;
use App\Http\Controllers\Admin\UserController as AdminUser;
use App\Http\Controllers\Admin\NotificationController as AdminNotification;
use App\Http\Controllers\Admin\CRMController as AdminCRM;
use App\Http\Controllers\Admin\ReportController as AdminReport;
use App\Http\Controllers\Admin\ShippingController as AdminShipping;
use App\Http\Controllers\Admin\SettingController as AdminSetting;
use App\Http\Controllers\Admin\InventoryController as AdminInventory;
use App\Http\Controllers\Admin\OfferController as AdminOffer;
use App\Http\Controllers\Admin\BannerController as AdminBanner;
use App\Http\Controllers\Admin\HomeSectionController as AdminHomeSection;


/*
|--------------------------------------------------------------------------
| CUSTOMER CONTROLLERS
|--------------------------------------------------------------------------
*/
use App\Http\Controllers\Customer\AuthController as CustomerAuth;
use App\Http\Controllers\Customer\HomeController as CustomerHome;
use App\Http\Controllers\Customer\ProductController as CustomerProduct;
use App\Http\Controllers\Customer\CartController as CustomerCart;
use App\Http\Controllers\Customer\CheckoutController as CustomerCheckout;
use App\Http\Controllers\Customer\WishlistController as CustomerWishlist;
use App\Http\Controllers\Customer\PageController as CustomerPage;
use App\Http\Controllers\Customer\AccountController as CustomerAccount;
use App\Http\Controllers\Customer\OrderController as CustomerOrder;
use App\Http\Controllers\Customer\UserController as CustomerUser;



/*
|--------------------------------------------------------------------------
| ADMIN PANEL ROUTES
|--------------------------------------------------------------------------
*/



Route::prefix('admin')->group(function () {

    /*
    |--------------------------------------------------------------------------
    | ADMIN AUTH
    |--------------------------------------------------------------------------
    */
    Route::get('/login', [AdminAuth::class, 'loginPage'])->name('admin.login');
    Route::post('/login', [AdminAuth::class, 'login'])->name('admin.login.submit');
    Route::post('/logout', [AdminAuth::class, 'logout'])->name('admin.logout');

    /*
    |--------------------------------------------------------------------------
    | AUTHENTICATED ADMIN ROUTES
    |--------------------------------------------------------------------------
    */
    Route::middleware('admin.auth')->group(function () {

        // Dashboard
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('admin.dashboard');
        // Add this route in your admin routes group
        Route::get('/dashboard/data', [AdminDashboard::class, 'getChartData'])->name('admin.dashboard.data');

        /*
        |--------------------------------------------------------------------------
        | CATEGORY MANAGEMENT
        |--------------------------------------------------------------------------
        */
        Route::prefix('categories')->group(function () {
            Route::get('/', [AdminCategory::class, 'index'])->name('admin.categories.index');
            Route::get('/create', [AdminCategory::class, 'create'])->name('admin.categories.create');
            Route::get('/{id}/edit', [AdminCategory::class, 'edit'])->name('admin.categories.edit');
            Route::get('/{id}', [AdminCategory::class, 'show'])->name('admin.categories.show');
        });

        /*
        |--------------------------------------------------------------------------
        | BRAND MANAGEMENT
        |--------------------------------------------------------------------------
        */
        Route::prefix('brands')->group(function () {
            Route::get('/', [AdminBrand::class, 'index'])->name('admin.brands.index');
        });

        /*
        |--------------------------------------------------------------------------
        | PRODUCT MANAGEMENT
        |--------------------------------------------------------------------------
        */
        Route::prefix('products')->group(function () {
            Route::get('/', [AdminProduct::class, 'index'])->name('admin.products.index');
            Route::get('/create', [AdminProduct::class, 'create'])->name('admin.products.create');
            Route::get('/{product}/edit', [AdminProduct::class, 'edit'])->name('admin.products.edit');
            Route::get('/{product}', [AdminProduct::class, 'edit'])->name('admin.products.show');
            Route::post('/', [AdminProduct::class, 'store'])->name('admin.products.store');
            Route::match(['post', 'put', 'patch'], '/{product}', [AdminProduct::class, 'update'])->name('admin.products.update');
            Route::delete('/{product}', [AdminProduct::class, 'destroy'])->name('admin.products.destroy');


            Route::get('/attributes', [AdminProduct::class, 'attributes'])->name('admin.products.attributes');
            Route::get('/specifications', [AdminProduct::class, 'specifications'])->name('admin.products.specifications');
            Route::get('/tags', [AdminProduct::class, 'tags'])->name('admin.products.tags');

            Route::get('/variants', [AdminProduct::class, 'variants'])->name('admin.products.variants');
            Route::get('/category/{category}/specifications', [AdminProduct::class, 'getCategorySpecifications'])->name('admin.products.category.specifications');
            Route::get('/category/{category}/attributes', [AdminProduct::class, 'getCategoryAttributes'])->name('admin.products.category.attributes');
            Route::get('/search', [AdminProduct::class, 'search'])->name('admin.products.search');
        });

        /*
 |--------------------------------------------------------------------------
 | ORDER MANAGEMENT
 |--------------------------------------------------------------------------
 */
        Route::prefix('orders')->name('admin.orders.')->group(function () {
            Route::get('/', [AdminOrder::class, 'index'])->name('index');
            Route::get('/data', [AdminOrder::class, 'getOrders'])->name('data');
            Route::get('/{order}', [AdminOrder::class, 'view'])->name('view');
            Route::post('/{order}/update-status', [AdminOrder::class, 'updateStatus'])->name('update-status');
            Route::post('/{order}/update-payment-status', [AdminOrder::class, 'updatePaymentStatus'])->name('update-payment-status');
            Route::post('/{order}/update-tracking', [AdminOrder::class, 'updateTracking'])->name('update-tracking');
            Route::delete('/{order}', [AdminOrder::class, 'destroy'])->name('destroy');
            Route::post('/bulk-delete', [AdminOrder::class, 'bulkDelete'])->name('bulk-delete');
            Route::get('/export', [AdminOrder::class, 'export'])->name('export');
            Route::get('/{order}/invoice', [AdminOrder::class, 'printInvoice'])->name('invoice');
        });

        /*
        |--------------------------------------------------------------------------
        | MEDIA MANAGER
        |--------------------------------------------------------------------------
        */
        Route::prefix('media')->group(function () {
            Route::get('/', [AdminMedia::class, 'index'])->name('admin.media.index');
            Route::get('/manager-data', [AdminMedia::class, 'getManagerData'])->name('admin.media.manager-data');
            Route::get('/data', [AdminMedia::class, 'getData'])->name('admin.media.data');
            Route::post('/upload', [AdminMedia::class, 'upload'])->name('admin.media.upload');
            Route::post('/assign-slot', [AdminMedia::class, 'assignSlot'])->name('admin.media.assign-slot');
            Route::post('/add-hero-slide', [AdminMedia::class, 'addHeroSlide'])->name('admin.media.add-hero-slide');
            Route::post('/hero-slide', [AdminMedia::class, 'addHeroSlide'])->name('admin.media.hero-slide');
            Route::get('/hero-slide/{id}', [AdminMedia::class, 'getHeroSlide'])->name('admin.media.get-hero-slide');
            Route::post('/hero-slide/{id}', [AdminMedia::class, 'updateHeroSlide'])->name('admin.media.update-hero-slide');
            Route::delete('/hero-slide/{id}', [AdminMedia::class, 'deleteHeroSlide'])->name('admin.media.delete-hero-slide');
            Route::post('/update-metadata/{id?}', [AdminMedia::class, 'updateMetadata'])->name('admin.media.update-metadata');
            Route::post('/detach-slot', [AdminMedia::class, 'detachSlot'])->name('admin.media.detach-slot');
            Route::post('/toggle-status/{id}', [AdminMedia::class, 'toggleStatus'])->name('admin.media.toggle-status');
            Route::post('/update-category/{id}', [AdminMedia::class, 'updateCategory'])->name('admin.media.update-category');
            Route::post('/testimonial', [AdminMedia::class, 'saveTestimonial'])->name('admin.media.testimonial.save');
            Route::get('/testimonial/{id}', [AdminMedia::class, 'getTestimonial'])->name('admin.media.testimonial.get');
            Route::delete('/testimonial/{id}', [AdminMedia::class, 'deleteTestimonial'])->name('admin.media.testimonial.delete');
            Route::post('/testimonial/{id}/toggle', [AdminMedia::class, 'toggleTestimonial'])->name('admin.media.testimonial.toggle');
            Route::post('/video-reel', [AdminMedia::class, 'addVideoReel'])->name('admin.media.add-video-reel');
            Route::post('/video-reel/add', [AdminMedia::class, 'addVideoReel']);
            Route::get('/video-reel/{id}', [AdminMedia::class, 'getVideoReel'])->name('admin.media.get-video-reel');
            Route::post('/video-reel/{id}', [AdminMedia::class, 'updateVideoReel'])->name('admin.media.update-video-reel');
            Route::post('/video-reel/update/{id}', [AdminMedia::class, 'updateVideoReel']);
            Route::delete('/video-reel/{id}', [AdminMedia::class, 'deleteVideoReel'])->name('admin.media.delete-video-reel');
            Route::post('/video-reel/{id}/toggle', [AdminMedia::class, 'toggleVideoReelStatus'])->name('admin.media.toggle-video-reel');
            Route::post('/video-reel/toggle-status/{id}', [AdminMedia::class, 'toggleVideoReelStatus']);
            Route::post('/blog-reels-settings', [AdminMedia::class, 'updateBlogReelsSettings'])->name('admin.media.blog-reels-settings');
            Route::post('/blog-reels/settings', [AdminMedia::class, 'updateBlogReelsSettings']);
            Route::delete('/{id}', [AdminMedia::class, 'destroy'])->name('admin.media.destroy');
        });



        /*
        |--------------------------------------------------------------------------
        | OFFERS MANAGEMENT
        |--------------------------------------------------------------------------
        */
        Route::prefix('offers')->group(function () {

            Route::get('/', [AdminOffer::class, 'index'])
                ->name('admin.offers.index');

            Route::get('/create', [AdminOffer::class, 'create'])
                ->name('admin.offers.create');


            Route::get('/edit', [AdminOffer::class, 'create'])
                ->name('admin.offers.edit');


        });


        /*
        |--------------------------------------------------------------------------
        | TAX SETTINGS
        |--------------------------------------------------------------------------
        */
        Route::prefix('taxes')->group(function () {
            Route::get('/', [AdminTax::class, 'index'])->name('admin.taxes.index');

        });

        /*
        |--------------------------------------------------------------------------
        | USER MANAGEMENT
        |--------------------------------------------------------------------------
        */
        Route::prefix('users')->name('admin.users.')->group(function () {

            // Pages
            Route::get('/', [AdminUser::class, 'index'])->name('index');
            Route::get('/create', [AdminUser::class, 'create'])->name('create');
            Route::get('/{user}/edit', [AdminUser::class, 'edit'])->name('edit');

            // AJAX / API (MUST BE BEFORE {user})
            Route::get('/data', [AdminUser::class, 'getCustomers'])->name('data');
            Route::post('/bulk-delete', [AdminUser::class, 'bulkDelete'])->name('bulk-delete');
            Route::post('/bulk-block', [AdminUser::class, 'bulkBlock'])->name('bulk-block');
            Route::get('/export', [AdminUser::class, 'export'])->name('export');

            Route::post('/{user}/toggle-status', [AdminUser::class, 'toggleStatus'])->name('toggle-status');
            Route::post('/{user}/toggle-block', [AdminUser::class, 'toggleBlock'])->name('toggle-block');

            // CRUD
            Route::post('/', [AdminUser::class, 'store'])->name('store');
            Route::put('/{user}', [AdminUser::class, 'update'])->name('update');
            Route::delete('/{user}', [AdminUser::class, 'destroy'])->name('destroy');
            Route::get('/{user}', [AdminUser::class, 'show'])->name('show');
        });



        /*
        |--------------------------------------------------------------------------
        | INVENTORY
        |--------------------------------------------------------------------------
        */
        Route::prefix('inventory')->group(function () {
            Route::get('/', [AdminInventory::class, 'index'])->name('admin.inventory.index');
            Route::get('/history', [AdminInventory::class, 'history'])->name('admin.inventory.history');
            Route::get('/{id}/update', [AdminInventory::class, 'updateStock'])->name('admin.inventory.update');
        });

        /*
        |--------------------------------------------------------------------------
        | NOTIFICATIONS
        |--------------------------------------------------------------------------
        */
        Route::get('/notifications', [AdminNotification::class, 'index'])->name('admin.notifications.index');

        /*
        |--------------------------------------------------------------------------
        | CRM
        |--------------------------------------------------------------------------
        */
        Route::prefix('crm')->group(function () {
            Route::get('/', [AdminCRM::class, 'index'])->name('admin.crm.index');
            Route::get('/popup', [AdminCRM::class, 'popup'])->name('admin.crm.popup');
            Route::get('/settings', [AdminCRM::class, 'settings'])->name('admin.crm.settings');

            // Banners
            Route::prefix('banners')->name('admin.banners.')->group(function () {
                Route::get('/', [AdminBanner::class, 'index'])->name('index');
                Route::get('/create', [AdminBanner::class, 'create'])->name('create');
                Route::post('/', [AdminBanner::class, 'store'])->name('store');
                Route::get('/{banner}/edit', [AdminBanner::class, 'edit'])->name('edit');
                Route::put('/{banner}', [AdminBanner::class, 'update'])->name('update');
                Route::delete('/{banner}', [AdminBanner::class, 'destroy'])->name('destroy');
                Route::post('/{banner}/toggle-status', [AdminBanner::class, 'toggleStatus'])->name('toggle-status');
            });

            // Home Sections
            Route::prefix('home-sections')->name('admin.home-sections.')->group(function () {
                Route::get('/', [AdminHomeSection::class, 'index'])->name('index');
                Route::get('/create', [AdminHomeSection::class, 'create'])->name('create');
                Route::post('/', [AdminHomeSection::class, 'store'])->name('store');
                Route::get('/{section}/edit', [AdminHomeSection::class, 'edit'])->name('edit');
                Route::put('/{section}', [AdminHomeSection::class, 'update'])->name('update');
                Route::delete('/{section}', [AdminHomeSection::class, 'destroy'])->name('destroy');
                Route::post('/{section}/toggle-status', [AdminHomeSection::class, 'toggleStatus'])->name('toggle-status');
            });
        });

        /*
        |--------------------------------------------------------------------------
        | REPORTS
        |--------------------------------------------------------------------------
        */
        Route::prefix('reports')->group(function () {
            Route::get('/', [AdminReport::class, 'index'])->name('admin.reports.index');
            Route::get('/sales', [AdminReport::class, 'sales'])->name('admin.reports.sales');
            Route::get('/customers', [AdminReport::class, 'customers'])->name('admin.reports.customers');
            Route::get('/products', [AdminReport::class, 'products'])->name('admin.reports.products');
        });

        /*
        |--------------------------------------------------------------------------
        | SHIPPING
        |--------------------------------------------------------------------------
        */
        Route::prefix('shipping')->group(function () {
            Route::get('/', [AdminShipping::class, 'index'])->name('admin.shipping.index');
            Route::get('/charges', [AdminShipping::class, 'charges'])->name('admin.shipping.charges');
        });

        /*
        |--------------------------------------------------------------------------
        | SETTINGS
        |--------------------------------------------------------------------------
        */
        Route::get('/settings', [AdminSetting::class, 'index'])->name('admin.settings.index');

        /*
        |--------------------------------------------------------------------------
        | PAGES MANAGEMENT
        |--------------------------------------------------------------------------
        */
        Route::resource('pages', App\Http\Controllers\Admin\PageController::class, ['as' => 'admin']);
        Route::resource('reviews', App\Http\Controllers\Admin\ReviewController::class, ['as' => 'admin']);
        Route::resource('testimonials', App\Http\Controllers\Admin\TestimonialController::class, ['as' => 'admin']);

        /*
        |--------------------------------------------------------------------------
        | CONTACT MESSAGES
        |--------------------------------------------------------------------------
        */
        Route::get('/contact-messages', [App\Http\Controllers\Admin\ContactMessageController::class, 'index'])->name('admin.contact-messages.index');
        Route::post('/contact-messages/settings', [App\Http\Controllers\Admin\ContactMessageController::class, 'updateSettings'])->name('admin.contact-messages.settings.update');
        Route::delete('/contact-messages/{id}', [App\Http\Controllers\Admin\ContactMessageController::class, 'destroy'])->name('admin.contact-messages.destroy');

    });

});


Route::get('/run-migration', function () {
    try {
        \Illuminate\Support\Facades\Artisan::call('migrate:fresh', ['--seed' => true]);
        return "Database migration and seeding completed successfully! You can now go back to your website.";
    } catch (\Exception $e) {
        return "Error: " . $e->getMessage();
    }
});

Route::get('/check-contact-messages', function () {
    return \App\Models\ContactMessage::count();
});


/*
|--------------------------------------------------------------------------
| CUSTOMER FRONTEND (NEXT.JS UNIFIED SERVING)
|--------------------------------------------------------------------------
*/
Route::get('/', function () {
    $indexPath = public_path('index.html');
    if (file_exists($indexPath)) {
        return response()->file($indexPath);
    }
    return view('welcome');
});

Route::get('/{any}', function ($any = '') {
    $path = trim($any, '/');

    // 1. Direct match with subfolder index.html (e.g. /shop -> public/shop/index.html)
    if ($path && file_exists(public_path($path . '/index.html'))) {
        return response()->file(public_path($path . '/index.html'));
    }

    // 2. Direct match with .html file (e.g. /shop -> public/shop.html)
    if ($path && file_exists(public_path($path . '.html'))) {
        return response()->file(public_path($path . '.html'));
    }

    // 3. Fallback to main index.html for client-side routing
    if (file_exists(public_path('index.html'))) {
        return response()->file(public_path('index.html'));
    }

    return view('app');
})->where('any', '^(?!admin|api).*$');
