<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Admin\AdminApiAuthController;
use App\Http\Controllers\Api\Admin\MediaController;
use App\Http\Controllers\Api\Admin\BrandController;
use App\Http\Controllers\Api\Admin\TaxClassController;
use App\Http\Controllers\Api\Admin\TaxRateController;
use App\Http\Controllers\Api\Admin\TagController;
use App\Http\Controllers\Api\Admin\AttributeController;
use App\Http\Controllers\Api\Admin\AttributeValueController;
use App\Http\Controllers\Api\Admin\SpecificationController;
use App\Http\Controllers\Api\Admin\SpecificationValueController;
use App\Http\Controllers\Api\Admin\SpecificationGroupController;
use App\Http\Controllers\Api\Admin\CategoryController;
use App\Http\Controllers\Api\Admin\ProductController;
use App\Http\Controllers\Api\Admin\SettingController;
use App\Http\Controllers\Api\Admin\OfferController;
use App\Http\Controllers\Api\Admin\InventoryController;



Route::prefix('admin')->middleware('api')->group(function () {

    // Public
    Route::post('login', [AdminApiAuthController::class, 'login']);

    // Protected
    Route::middleware('admin.api')->group(function () {
        Route::get('dashboard', fn() => [
            'message' => 'Admin API Dashboard'
        ]);

        Route::post('logout', [AdminApiAuthController::class, 'logout']);
        Route::post('profile/update', [AdminApiAuthController::class, 'updateProfile']);


        // Media Module Routes
        Route::prefix('media')->group(function () {
            Route::get('/', [MediaController::class, 'index']);
            Route::post('/upload', [MediaController::class, 'upload']);
            Route::put('/{id}', [MediaController::class, 'update']);
            Route::delete('/{id}', [MediaController::class, 'destroy']);
            Route::post('/bulk-delete', [MediaController::class, 'bulkDelete']);
            Route::get('/statistics', [MediaController::class, 'statistics']);
        });

        // Brand Routes
        Route::prefix('brands')->group(function () {
            Route::get('/dropdown', [BrandController::class, 'dropdown']);

            Route::get('/', [BrandController::class, 'index']);
            Route::get('/statistics', [BrandController::class, 'statistics']);
            Route::post('/', [BrandController::class, 'store']);
            Route::get('/{id}', [BrandController::class, 'show']);
            Route::put('/{id}', [BrandController::class, 'update']);
            Route::delete('/{id}', [BrandController::class, 'destroy']);

            // Status management
            Route::post('/{id}/status', [BrandController::class, 'updateStatus']);
            Route::post('/{id}/featured', [BrandController::class, 'updateFeatured']);

            // Bulk operations
            Route::post('/bulk-status', [BrandController::class, 'bulkStatus']);
            Route::post('/bulk-featured', [BrandController::class, 'bulkFeatured']);
            Route::post('/bulk-delete', [BrandController::class, 'bulkDelete']);


        });



        // Tax Classes Routes
        Route::prefix('tax-classes')->group(function () {
            Route::get('/', [TaxClassController::class, 'index']);
            Route::get('/dropdown', [TaxClassController::class, 'dropdown']);
            Route::get('/statistics', [TaxClassController::class, 'statistics']);
            Route::post('/', [TaxClassController::class, 'store']);
            Route::get('/{id}', [TaxClassController::class, 'show']);
            Route::put('/{id}', [TaxClassController::class, 'update']);
            Route::delete('/{id}', [TaxClassController::class, 'destroy']);
            Route::post('/{id}/toggle-default', [TaxClassController::class, 'toggleDefault']);
            Route::post('/bulk-delete', [TaxClassController::class, 'bulkDelete']);
        });

        // Tax Rates Routes
        Route::prefix('tax-rates')->group(function () {
            Route::get('/', [TaxRateController::class, 'index']);
            Route::get('/types', [TaxRateController::class, 'types']);
            Route::get('/scopes', [TaxRateController::class, 'scopes']);
            Route::get('/statistics', [TaxRateController::class, 'statistics']);
            Route::post('/', [TaxRateController::class, 'store']);
            Route::get('/{id}', [TaxRateController::class, 'show']);
            Route::put('/{id}', [TaxRateController::class, 'update']);
            Route::delete('/{id}', [TaxRateController::class, 'destroy']);
            Route::post('/{id}/toggle-status', [TaxRateController::class, 'toggleStatus']);
            Route::post('/bulk-delete', [TaxRateController::class, 'bulkDelete']);
            Route::post('/bulk-status', [TaxRateController::class, 'bulkStatus']);
            Route::post('/calculate', [TaxRateController::class, 'calculate']);
        });


        // Tag Routes
        Route::prefix('tags')->group(function () {
            Route::get('/', [TagController::class, 'index']);
            Route::get('/dropdown', [TagController::class, 'dropdown']);
            Route::get('/statistics', [TagController::class, 'statistics']);
            Route::get('/popular', [TagController::class, 'popular']);
            Route::post('/', [TagController::class, 'store']);
            Route::get('/{id}', [TagController::class, 'show']);
            Route::put('/{id}', [TagController::class, 'update']);
            Route::delete('/{id}', [TagController::class, 'destroy']);

            // Status operations
            Route::post('/{id}/status', [TagController::class, 'updateStatus']);
            Route::post('/{id}/featured', [TagController::class, 'updateFeatured']);

            // Bulk operations
            Route::post('/bulk-status', [TagController::class, 'bulkStatus']);
            Route::post('/bulk-featured', [TagController::class, 'bulkFeatured']);
            Route::post('/bulk-delete', [TagController::class, 'bulkDelete']);
        });


        // Attribute Routes
        Route::prefix('attributes')->group(function () {
            Route::get('/', [AttributeController::class, 'index']);
            Route::get('/dropdown', [AttributeController::class, 'dropdown']);
            Route::get('/types', [AttributeController::class, 'types']);
            Route::get('/statistics', [AttributeController::class, 'statistics']);
            Route::get('/for-product-variants', [AttributeController::class, 'forProductVariants']);
            Route::post('/', [AttributeController::class, 'store']);
            Route::get('/{id}', [AttributeController::class, 'show']);
            Route::put('/{id}', [AttributeController::class, 'update']);
            Route::delete('/{id}', [AttributeController::class, 'destroy']);

            // Status operations
            Route::post('/{id}/toggle-status', [AttributeController::class, 'toggleStatus']);
            Route::post('/{id}/toggle-variant', [AttributeController::class, 'toggleVariant']);
            Route::post('/{id}/toggle-filterable', [AttributeController::class, 'toggleFilterable']);

            // Bulk operations
            Route::post('/bulk-update', [AttributeController::class, 'bulkUpdate']);
            Route::post('/bulk-delete', [AttributeController::class, 'bulkDelete']);

            // Attribute Values Routes
            Route::prefix('/{attribute}/values')->group(function () {
                Route::get('/', [AttributeValueController::class, 'index']);
                Route::post('/', [AttributeValueController::class, 'store']);
                Route::get('/{id}', [AttributeValueController::class, 'show']);
                Route::put('/{id}', [AttributeValueController::class, 'update']);
                Route::delete('/{id}', [AttributeValueController::class, 'destroy']);
                Route::post('/{id}/toggle-status', [AttributeValueController::class, 'toggleStatus']);
                Route::post('/bulk-update', [AttributeValueController::class, 'bulkUpdate']);
                Route::post('/bulk-delete', [AttributeValueController::class, 'bulkDelete']);
            });
        });


        // Specification Routes
        Route::prefix('specifications')->group(function () {
            Route::get('/', [SpecificationController::class, 'index']);
            Route::get('/dropdown', [SpecificationController::class, 'dropdown']);
            Route::get('/input-types', [SpecificationController::class, 'inputTypes']);
            Route::get('/statistics', [SpecificationController::class, 'statistics']);
            Route::get('/for-product-creation', [SpecificationController::class, 'forProductCreation']);
            Route::post('/', [SpecificationController::class, 'store']);
            Route::get('/{id}', [SpecificationController::class, 'show']);
            Route::put('/{id}', [SpecificationController::class, 'update']);
            Route::delete('/{id}', [SpecificationController::class, 'destroy']);

            // Status operations
            Route::post('/{id}/toggle-status', [SpecificationController::class, 'toggleStatus']);
            Route::post('/{id}/toggle-required', [SpecificationController::class, 'toggleRequired']);
            Route::post('/{id}/toggle-filterable', [SpecificationController::class, 'toggleFilterable']);

            // Bulk operations
            Route::post('/bulk-update', [SpecificationController::class, 'bulkUpdate']);
            Route::post('/bulk-delete', [SpecificationController::class, 'bulkDelete']);

            // Specification Values Routes
            Route::prefix('/{specification}/values')->group(function () {
                Route::get('/', [SpecificationValueController::class, 'index']);
                Route::post('/', [SpecificationValueController::class, 'store']);
                Route::get('/{id}', [SpecificationValueController::class, 'show']);
                Route::put('/{id}', [SpecificationValueController::class, 'update']);
                Route::delete('/{id}', [SpecificationValueController::class, 'destroy']);
                Route::post('/{id}/toggle-status', [SpecificationValueController::class, 'toggleStatus']);
                Route::post('/bulk-update', [SpecificationValueController::class, 'bulkUpdate']);
                Route::post('/bulk-delete', [SpecificationValueController::class, 'bulkDelete']);
            });
        });

        // Specification Group Routes
        Route::prefix('specification-groups')->group(function () {
            Route::get('/', [SpecificationGroupController::class, 'index']);
            Route::get('/dropdown', [SpecificationGroupController::class, 'dropdown']);
            Route::get('/statistics', [SpecificationGroupController::class, 'statistics']);
            Route::get('/for-category-assignment', [SpecificationGroupController::class, 'forCategoryAssignment']);
            Route::post('/', [SpecificationGroupController::class, 'store']);
            Route::get('/{id}', [SpecificationGroupController::class, 'show']);
            Route::put('/{id}', [SpecificationGroupController::class, 'update']);
            Route::delete('/{id}', [SpecificationGroupController::class, 'destroy']);
            Route::post('/{id}/toggle-status', [SpecificationGroupController::class, 'toggleStatus']);
            Route::post('/bulk-update', [SpecificationGroupController::class, 'bulkUpdate']);
            Route::post('/bulk-delete', [SpecificationGroupController::class, 'bulkDelete']);
        });

        // Category Routes
        Route::prefix('categories')->group(function () {
            Route::get('/', [CategoryController::class, 'index']);
            Route::get('/dropdown', [CategoryController::class, 'dropdown']);
            Route::get('/tree', [CategoryController::class, 'tree']);
            Route::get('/statistics', [CategoryController::class, 'statistics']);
            Route::get('/{id}', [CategoryController::class, 'show']);
            Route::post('/', [CategoryController::class, 'store']);
            Route::put('/{id}', [CategoryController::class, 'update']);
            Route::delete('/{id}', [CategoryController::class, 'destroy']);

            // Status operations
            Route::post('/{id}/status', [CategoryController::class, 'updateStatus']);
            Route::post('/bulk-status', [CategoryController::class, 'bulkUpdateStatus']);

            // Bulk operations
            Route::post('/bulk-delete', [CategoryController::class, 'bulkDelete']);

            // Specification groups
            Route::get('/{id}/spec-groups', [CategoryController::class, 'getSpecGroups']);
            Route::post('/{id}/spec-groups', [CategoryController::class, 'updateSpecGroups']);

            // Attributes
            Route::get('/{id}/attributes', [CategoryController::class, 'getAttributes']);
            Route::post('/{id}/attributes', [CategoryController::class, 'updateAttributes']);
        });



        // Product Routes
        Route::prefix('products')->group(function () {

            // Dropdown & statistics
            Route::get('/dropdown', [ProductController::class, 'dropdown']);
            Route::get('/statistics', [ProductController::class, 'statistics']);



            Route::get('/category/{category}/specifications', [ProductController::class, 'getCategorySpecifications']);
            Route::get('/category/{category}/attributes', [ProductController::class, 'getCategoryAttributes']);
            Route::post('/generate-variants', [ProductController::class, 'generateVariants']);


            // Core CRUD
            Route::get('/', [ProductController::class, 'index']);
            Route::post('/', [ProductController::class, 'store']);
            Route::get('/{product}', [ProductController::class, 'show']);
            Route::put('/{product}', [ProductController::class, 'update']);
            Route::delete('/{product}', [ProductController::class, 'destroy']);

            // Product logic
            Route::post('/generate-variants', [ProductController::class, 'generateVariants']);
            Route::post('/check-sku', [ProductController::class, 'checkSku']);

            // Status & featured
            Route::post('/{product}/status', [ProductController::class, 'toggleStatus']);
            Route::post('/{product}/featured', [ProductController::class, 'toggleFeatured']);

            // Category-based attributes
            Route::get('/category/{category}/attributes', [ProductController::class, 'getCategoryAttributes']);

            // Bulk actions
            Route::post('/bulk-update', [ProductController::class, 'bulkUpdate']);
            Route::post('/bulk-delete', [ProductController::class, 'bulkDelete']);
        });


        // Inventory Routes
        Route::prefix('inventory')->group(function () {
            Route::get('/', [InventoryController::class, 'index']);
            Route::get('/statistics', [InventoryController::class, 'statistics']);
            Route::get('/history', [InventoryController::class, 'history']);
            Route::get('/history/statistics', [InventoryController::class, 'historyStatistics']);
            Route::post('/update', [InventoryController::class, 'updateStock']);
            Route::post('/bulk-update', [InventoryController::class, 'bulkUpdateStock']);
        });


        // Settings Routes
        Route::prefix('settings')->group(function () {
            Route::get('/', [SettingController::class, 'index']);
            Route::get('/dropdown', [SettingController::class, 'dropdown']);
            Route::get('/groups', [SettingController::class, 'groups']);
            Route::get('/group/{group}', [SettingController::class, 'getByGroup']);
            Route::get('/{key}', [SettingController::class, 'show']);
            Route::put('/{key}', [SettingController::class, 'update']);
            Route::post('/bulk-update', [SettingController::class, 'bulkUpdate']);
            Route::post('/reset', [SettingController::class, 'reset']);
        });


        // Offer Routes
        Route::prefix('offers')->group(function () {
            Route::get('/', [OfferController::class, 'index']);
            Route::get('/dropdown', [OfferController::class, 'dropdown']);
            Route::get('/types', [OfferController::class, 'types']);
            Route::get('/statistics', [OfferController::class, 'statistics']);
            Route::get('/validate-code', [OfferController::class, 'validateCode']);
            Route::post('/', [OfferController::class, 'store']);
            Route::get('/{id}', [OfferController::class, 'show']);
            Route::put('/{id}', [OfferController::class, 'update']);
            Route::delete('/{id}', [OfferController::class, 'destroy']);

            // Status operations
            Route::post('/{id}/status', [OfferController::class, 'updateStatus']);
            Route::post('/{id}/auto-apply', [OfferController::class, 'updateAutoApply']);

            // Bulk operations
            Route::post('/bulk-update', [OfferController::class, 'bulkUpdate']);
            Route::post('/bulk-delete', [OfferController::class, 'bulkDelete']);
        });


    });


});

