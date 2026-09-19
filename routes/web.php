<?php

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;
use App\Http\Controllers\Merchant\DashboardController as MerchantDashboard;
use App\Http\Controllers\Merchant\CategoryController;
use App\Http\Controllers\Merchant\ProductController;
use App\Http\Controllers\Merchant\OrderController;
use App\Http\Controllers\Merchant\InventoryController;
use App\Http\Controllers\Merchant\CouponController;          // ← أضف هذا
use App\Http\Controllers\Merchant\OfferController; 
use App\Http\Controllers\Merchant\StoreSettingsController;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
})->name('home');

// ============ Routes محمية بالمصادقة ============
Route::middleware(['auth', 'verified'])->group(function () {

    // إعادة التوجيه حسب الدور
    Route::get('/dashboard', function () {
        return match (auth()->user()->role) {
            'admin' => redirect()->route('admin.dashboard'),
            'merchant' => redirect()->route('merchant.dashboard'),
            default => redirect()->route('customer.dashboard'),
        };
    })->name('dashboard');

    // ============ المشرف ============
    Route::middleware('role:admin')->prefix('admin')->name('admin.')->group(function () {
        Route::get('/dashboard', [AdminDashboard::class, 'index'])->name('dashboard');
    });

    // ============ التاجر ============
    Route::middleware('role:merchant')->prefix('merchant')->name('merchant.')->group(function () {
        Route::get('/dashboard', [MerchantDashboard::class, 'index'])->name('dashboard');
        Route::resource('categories', CategoryController::class);
        Route::resource('products', ProductController::class);
        Route::delete('products/images/{image}', [ProductController::class, 'deleteImage'])
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
    Route::resource('offers', OfferController::class)->except(['show']);

    // Settings
    Route::prefix('settings')->name('settings.')->group(function () {
        Route::get('/', [StoreSettingsController::class, 'index'])->name('index');
        Route::put('/', [StoreSettingsController::class, 'update'])->name('update');

        Route::get('/policies', [StoreSettingsController::class, 'policies'])->name('policies');
        Route::put('/policies', [StoreSettingsController::class, 'updatePolicies'])->name('policies.update');

        // Branches
        Route::get('/branches', [StoreSettingsController::class, 'branches'])->name('branches');
        Route::post('/branches', [StoreSettingsController::class, 'storeBranch'])->name('branches.store');
        Route::put('/branches/{branch}', [StoreSettingsController::class, 'updateBranch'])->name('branches.update');
        Route::delete('/branches/{branch}', [StoreSettingsController::class, 'destroyBranch'])->name('branches.destroy');

        // Shipping
        Route::get('/shipping', [StoreSettingsController::class, 'shipping'])->name('shipping');
        Route::post('/shipping', [StoreSettingsController::class, 'storeShipping'])->name('shipping.store');
        Route::put('/shipping/{zone}', [StoreSettingsController::class, 'updateShipping'])->name('shipping.update');
        Route::delete('/shipping/{zone}', [StoreSettingsController::class, 'destroyShipping'])->name('shipping.destroy');
    });


});

    // ============ الزبون ============
    Route::middleware('role:customer')->prefix('customer')->name('customer.')->group(function () {
        Route::get('/dashboard', [CustomerDashboard::class, 'index'])->name('dashboard');
    });
});

require __DIR__.'/auth.php';

Route::middleware('auth')->group(function () {
    Route::get('/profile', function () {
        return view('profile.edit');
    })->name('profile.edit');
});