<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\OfferController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;
use App\Http\Controllers\Merchant\DashboardController as MerchantDashboard;
use App\Http\Controllers\Merchant\CategoryController;
use App\Http\Controllers\Merchant\ProductController as MerchantProductController;
use App\Http\Controllers\Merchant\OrderController;
use App\Http\Controllers\Merchant\InventoryController;
use App\Http\Controllers\Merchant\CouponController;
use App\Http\Controllers\Merchant\OfferController as MerchantOfferController;
use App\Http\Controllers\Merchant\StoreSettingsController;

use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Public Routes - الواجهة الأمامية
|--------------------------------------------------------------------------
*/

Route::get('/', [HomeController::class, 'index'])->name('home');

// Products
Route::get('/products', [ProductController::class, 'index'])->name('products.index');
Route::get('/products/search', [ProductController::class, 'search'])->name('products.search');
Route::get('/products/{slug}', [ProductController::class, 'show'])->name('products.show');

// Stores
Route::get('/stores', [StoreController::class, 'index'])->name('stores.index');
Route::get('/stores/{store}', [StoreController::class, 'show'])->name('stores.show');

// Offers
Route::get('/offers', [OfferController::class, 'index'])->name('offers.index');

// Cart (Placeholder - سننشئه قريباً)
Route::get('/cart', fn() => 'السلة - قريباً')->name('cart.index');
Route::post('/cart/add', fn() => response()->json(['success' => false, 'message' => 'قيد التطوير']))->name('cart.add');

/*
|--------------------------------------------------------------------------
| Authenticated Routes
|--------------------------------------------------------------------------
*/

Route::middleware(['auth', 'verified'])->group(function () {

    Route::get('/dashboard', function () {
        return match (auth()->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'merchant' => redirect()->route('merchant.dashboard'),
            default => redirect()->route('customer.dashboard'),
        };
    })->name('dashboard');

    /*
    |--------------------------------------------------------------------------
    | Admin Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    });

    /*
    |--------------------------------------------------------------------------
    | Merchant Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:merchant')->prefix('merchant')->name('merchant.')->group(function () {

        Route::get('/dashboard', [MerchantDashboard::class, 'index'])->name('dashboard');

        // Categories
        Route::resource('categories', CategoryController::class);

        // Products
        Route::resource('products', MerchantProductController::class);
        Route::delete('products/images/{image}', [MerchantProductController::class, 'deleteImage'])
            ->name('products.images.delete');

        // Orders
        Route::get('orders', [OrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [OrderController::class, 'show'])->name('orders.show');
        Route::put('orders/{order}/status', [OrderController::class, 'updateStatus'])->name('orders.updateStatus');

        // Inventory
        Route::prefix('inventory')->name('inventory.')->group(function () {
            Route::get('/', [InventoryController::class, 'index'])->name('index');
            Route::get('/low-stock', [InventoryController::class, 'lowStock'])->name('lowStock');
            Route::get('/logs', [InventoryController::class, 'logs'])->name('logs');
            Route::get('/logs/{variant}', [InventoryController::class, 'logs'])->name('variantLogs');
            Route::put('/variants/{variant}/stock', [InventoryController::class, 'updateStock'])->name('updateStock');
        });

        // Coupons
        Route::resource('coupons', CouponController::class)->except(['show']);

        // Offers
        Route::resource('offers', MerchantOfferController::class)->except(['show']);

        // Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [StoreSettingsController::class, 'index'])->name('index');
            Route::put('/', [StoreSettingsController::class, 'update'])->name('update');

            Route::get('/policies', [StoreSettingsController::class, 'policies'])->name('policies');
            Route::put('/policies', [StoreSettingsController::class, 'updatePolicies'])->name('policies.update');

            Route::get('/branches', [StoreSettingsController::class, 'branches'])->name('branches');
            Route::post('/branches', [StoreSettingsController::class, 'storeBranch'])->name('branches.store');
            Route::put('/branches/{branch}', [StoreSettingsController::class, 'updateBranch'])->name('branches.update');
            Route::delete('/branches/{branch}', [StoreSettingsController::class, 'destroyBranch'])->name('branches.destroy');

            Route::get('/shipping', [StoreSettingsController::class, 'shipping'])->name('shipping');
            Route::post('/shipping', [StoreSettingsController::class, 'storeShipping'])->name('shipping.store');
            Route::put('/shipping/{zone}', [StoreSettingsController::class, 'updateShipping'])->name('shipping.update');
            Route::delete('/shipping/{zone}', [StoreSettingsController::class, 'destroyShipping'])->name('shipping.destroy');
        });
    });

    /*
    |--------------------------------------------------------------------------
    | Customer Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:customer')->prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [CustomerDashboard::class, 'index'])->name('dashboard');

        Route::get('/orders', fn() => 'طلباتي - قريباً')->name('orders.index');
        Route::get('/wishlist', fn() => 'المفضلة - قريباً')->name('wishlist');
    });
});

require __DIR__.'/auth.php';