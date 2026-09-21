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



$adminRoutes = function () {
    Route::get('/', function () {
        return redirect()->route('admin.dashboard');
    });

    /*
    |--------------------------------------------------------------------------
    | ADMIN AUTH
    |--------------------------------------------------------------------------
    */
    Route::match(['get', 'head'], '/signin', [AdminAuth::class, 'loginPage'])->name('admin.signin');
    Route::match(['get', 'head'], '/signin/{any}', [AdminAuth::class, 'loginPage'])->where('any', '.*');
    Route::post('/signin', [AdminAuth::class, 'login'])->name('admin.signin.submit');
    Route::post('/signin/{any}', [AdminAuth::class, 'login'])->where('any', '.*');

    Route::match(['get', 'head'], '/login', function () {
        return redirect()->route('admin.signin');
    })->name('admin.login');
    Route::match(['get', 'head'], '/login/{any}', function () {
        return redirect()->route('admin.signin');
    })->where('any', '.*');
    Route::post('/login', [AdminAuth::class, 'login'])->name('admin.login.submit');
    Route::post('/login/{any}', [AdminAuth::class, 'login'])->where('any', '.*');

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
            Route::match(['post', 'put'], '/add-hero-slide', [AdminMedia::class, 'addHeroSlide'])->name('admin.media.add-hero-slide');
            Route::match(['post', 'put'], '/hero-slide', [AdminMedia::class, 'addHeroSlide'])->name('admin.media.hero-slide');
            Route::get('/hero-slide/{id}', [AdminMedia::class, 'getHeroSlide'])->name('admin.media.get-hero-slide');
            Route::match(['post', 'put', 'patch'], '/hero-slide/{id}', [AdminMedia::class, 'updateHeroSlide'])->name('admin.media.update-hero-slide');
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
            
            // Custom Crochet Banner (Homepage)
            Route::post('/homepage/custom-crochet', [AdminMedia::class, 'saveCustomCrochet'])->name('admin.media.homepage.custom-crochet.save');
            Route::get('/homepage/custom-crochet', [AdminMedia::class, 'getCustomCrochet'])->name('admin.media.homepage.custom-crochet.get');

            // About Page Routes
            Route::post('/about/story', [AdminMedia::class, 'saveAboutStory'])->name('admin.media.about.story.save');
            Route::get('/about/story', [AdminMedia::class, 'getAboutStory'])->name('admin.media.about.story.get');
            Route::post('/about/craft-pillars/header', [AdminMedia::class, 'saveCraftPillarsHeader'])->name('admin.media.about.craft-pillars.header');
            Route::post('/about/craft-pillars', [AdminMedia::class, 'addCraftPillar'])->name('admin.media.about.craft-pillars.add');
            Route::get('/about/craft-pillars/{id}', [AdminMedia::class, 'getCraftPillar'])->name('admin.media.about.craft-pillars.get');
            Route::post('/about/craft-pillars/{id}', [AdminMedia::class, 'updateCraftPillar'])->name('admin.media.about.craft-pillars.update');
            Route::delete('/about/craft-pillars/{id}', [AdminMedia::class, 'deleteCraftPillar'])->name('admin.media.about.craft-pillars.delete');
            Route::post('/about/craft-pillars/{id}/toggle', [AdminMedia::class, 'toggleCraftPillar'])->name('admin.media.about.craft-pillars.toggle');
            Route::post('/about/craft-pillars/reorder', [AdminMedia::class, 'reorderCraftPillars'])->name('admin.media.about.craft-pillars.reorder');

            // Contact Page Routes
            Route::post('/contact/intro', [AdminMedia::class, 'saveContactIntro'])->name('admin.media.contact.intro.save');
            Route::get('/contact/intro', [AdminMedia::class, 'getContactIntro'])->name('admin.media.contact.intro.get');

            Route::get('/contact/info/header', [AdminMedia::class, 'getContactInfoHeader'])->name('admin.media.contact.info.header.get');
            Route::post('/contact/info/header', [AdminMedia::class, 'saveContactInfoHeader'])->name('admin.media.contact.info.header');
            Route::post('/contact/info', [AdminMedia::class, 'addContactInfoItem'])->name('admin.media.contact.info.add');
            Route::post('/contact/info/items', [AdminMedia::class, 'addContactInfoItem'])->name('admin.media.contact.info.items.add');
            Route::get('/contact/info/{id}', [AdminMedia::class, 'getContactInfoItem'])->name('admin.media.contact.info.get');
            Route::get('/contact/info/items/{id}', [AdminMedia::class, 'getContactInfoItem'])->name('admin.media.contact.info.items.get');
            Route::post('/contact/info/{id}', [AdminMedia::class, 'updateContactInfoItem'])->name('admin.media.contact.info.update');
            Route::post('/contact/info/items/{id}', [AdminMedia::class, 'updateContactInfoItem'])->name('admin.media.contact.info.items.update');
            Route::delete('/contact/info/{id}', [AdminMedia::class, 'deleteContactInfoItem'])->name('admin.media.contact.info.delete');
            Route::delete('/contact/info/items/{id}', [AdminMedia::class, 'deleteContactInfoItem'])->name('admin.media.contact.info.items.delete');
            Route::post('/contact/info/{id}/toggle', [AdminMedia::class, 'toggleContactInfoItem'])->name('admin.media.contact.info.toggle');
            Route::post('/contact/info/items/{id}/toggle', [AdminMedia::class, 'toggleContactInfoItem'])->name('admin.media.contact.info.items.toggle');
            Route::post('/contact/info/reorder', [AdminMedia::class, 'reorderContactInfoItems'])->name('admin.media.contact.info.reorder');

            Route::post('/contact/form', [AdminMedia::class, 'saveContactFormSettings'])->name('admin.media.contact.form.save');
            Route::get('/contact/form', [AdminMedia::class, 'getContactFormSettings'])->name('admin.media.contact.form.get');

            Route::get('/contact/faqs/header', [AdminMedia::class, 'getContactFaqsHeader'])->name('admin.media.contact.faqs.header.get');
            Route::post('/contact/faqs/header', [AdminMedia::class, 'saveContactFaqsHeader'])->name('admin.media.contact.faqs.header');
            Route::post('/contact/faqs', [AdminMedia::class, 'addContactFaq'])->name('admin.media.contact.faqs.add');
            Route::post('/contact/faqs/items', [AdminMedia::class, 'addContactFaq'])->name('admin.media.contact.faqs.items.add');
            Route::get('/contact/faqs/{id}', [AdminMedia::class, 'getContactFaq'])->name('admin.media.contact.faqs.get');
            Route::get('/contact/faqs/items/{id}', [AdminMedia::class, 'getContactFaq'])->name('admin.media.contact.faqs.items.get');
            Route::post('/contact/faqs/{id}', [AdminMedia::class, 'updateContactFaq'])->name('admin.media.contact.faqs.update');
            Route::post('/contact/faqs/items/{id}', [AdminMedia::class, 'updateContactFaq'])->name('admin.media.contact.faqs.items.update');
            Route::delete('/contact/faqs/{id}', [AdminMedia::class, 'deleteContactFaq'])->name('admin.media.contact.faqs.delete');
            Route::delete('/contact/faqs/items/{id}', [AdminMedia::class, 'deleteContactFaq'])->name('admin.media.contact.faqs.items.delete');
            Route::post('/contact/faqs/{id}/toggle', [AdminMedia::class, 'toggleContactFaq'])->name('admin.media.contact.faqs.toggle');
            Route::post('/contact/faqs/items/{id}/toggle', [AdminMedia::class, 'toggleContactFaq'])->name('admin.media.contact.faqs.items.toggle');
            Route::post('/contact/faqs/reorder', [AdminMedia::class, 'reorderContactFaqs'])->name('admin.media.contact.faqs.reorder');
            
            // Footer Settings Routes
            Route::get('/footer/settings', [AdminMedia::class, 'getFooterSettings'])->name('admin.media.footer.settings.get');
            Route::post('/footer/settings', [AdminMedia::class, 'saveFooterSettings'])->name('admin.media.footer.settings.save');

            // Navbar Settings Routes
            Route::get('/navbar/settings', [AdminMedia::class, 'getNavbarSettings'])->name('admin.media.navbar.settings.get');
            Route::post('/navbar/settings', [AdminMedia::class, 'saveNavbarSettings'])->name('admin.media.navbar.settings.save');

            // Custom Order Items Routes
            Route::post('/custom-order/items', [AdminMedia::class, 'addCustomOrderItem'])->name('admin.media.custom-order.items.add');
            Route::get('/custom-order/items/{id}', [AdminMedia::class, 'getCustomOrderItem'])->name('admin.media.custom-order.items.get');
            Route::post('/custom-order/items/{id}', [AdminMedia::class, 'updateCustomOrderItem'])->name('admin.media.custom-order.items.update');
            Route::delete('/custom-order/items/{id}', [AdminMedia::class, 'deleteCustomOrderItem'])->name('admin.media.custom-order.items.delete');
            Route::post('/custom-order/items/{id}/toggle', [AdminMedia::class, 'toggleCustomOrderItem'])->name('admin.media.custom-order.items.toggle');

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
};

Route::prefix('admin')->group($adminRoutes);
Route::prefix('knottele/admin')->as('knottele.')->group($adminRoutes);

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

    // Strip knottele prefix if present (e.g. /knottele/shop -> shop)
    if (str_starts_with($path, 'knottele/')) {
        $path = substr($path, strlen('knottele/'));
    } elseif ($path === 'knottele') {
        $path = '';
    }

    // 1. Direct match with static asset file (e.g. /icon.png -> public/icon.png, /favicon.ico -> public/favicon.ico)
    if ($path && file_exists(public_path($path)) && !is_dir(public_path($path))) {
        return response()->file(public_path($path));
    }

    // 2. Direct match with subfolder index.html (e.g. /shop -> public/shop/index.html)
    if ($path && file_exists(public_path($path . '/index.html'))) {
        return response()->file(public_path($path . '/index.html'));
    }

    // 3. Direct match with .html file (e.g. /shop -> public/shop.html)
    if ($path && file_exists(public_path($path . '.html'))) {
        return response()->file(public_path($path . '.html'));
    }

    // 3b. Resilient product route fallback: serve product template and RSC tree so client-side React never redirects to home
    if (str_starts_with($path, 'product/')) {
        if (str_contains($path, '__next.') || str_ends_with($path, '.txt')) {
            $fallbackTxt = glob(public_path('product/*/' . basename($path)));
            if (!empty($fallbackTxt)) {
                return response()->file($fallbackTxt[0], [
                    'Content-Type' => 'text/plain; charset=utf-8',
                ]);
            }
        }
        $productIndex = glob(public_path('product/*/index.html'));
        if (!empty($productIndex)) {
            return response()->file($productIndex[0]);
        }
    }

    // 3c. Resilient category route fallback
    if (str_starts_with($path, 'category/')) {
        if (str_contains($path, '__next.') || str_ends_with($path, '.txt')) {
            $fallbackTxt = glob(public_path('category/*/' . basename($path)));
            if (!empty($fallbackTxt)) {
                return response()->file($fallbackTxt[0], [
                    'Content-Type' => 'text/plain; charset=utf-8',
                ]);
            }
        }
        $catIndex = glob(public_path('category/*/index.html'));
        if (!empty($catIndex)) {
            return response()->file($catIndex[0]);
        }
    }

    // 4. If request is for a missing static asset or chunk, return real 404 (never HTML)
    if (preg_match('/\.(js|css|png|jpg|jpeg|gif|svg|ico|woff|woff2|ttf|eot|map|json|txt)$/i', $path) || str_starts_with($path, '_next/')) {
        abort(404);
    }

    // 5. Fallback to main index.html for client-side routing
    if (file_exists(public_path('index.html'))) {
        return response()->file(public_path('index.html'));
    }

    return view('app');
})->where('any', '^(?!admin|api|knottele/admin|knottele/api).*$');
