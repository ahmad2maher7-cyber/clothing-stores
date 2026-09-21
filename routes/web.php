<?php

use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\StoreController;
use App\Http\Controllers\OfferController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\WishlistController;
use App\Http\Controllers\PageController;

use App\Http\Controllers\Admin\DashboardController as AdminDashboard;
use App\Http\Controllers\Admin\UserController as AdminUserController;
use App\Http\Controllers\Admin\StoreController as AdminStoreController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;

use App\Http\Controllers\Customer\DashboardController as CustomerDashboard;
use App\Http\Controllers\Customer\OrderController as CustomerOrderController;
use App\Http\Controllers\Customer\ProfileController as CustomerProfileController;
use App\Http\Controllers\Customer\ReviewController as CustomerReviewController;

use App\Http\Controllers\Merchant\DashboardController as MerchantDashboard;
use App\Http\Controllers\Merchant\CategoryController;
use App\Http\Controllers\Merchant\ProductController as MerchantProductController;
use App\Http\Controllers\Merchant\OrderController;
use App\Http\Controllers\Merchant\InventoryController;
use App\Http\Controllers\Merchant\CouponController;
use App\Http\Controllers\Merchant\OfferController as MerchantOfferController;
use App\Http\Controllers\Merchant\StoreSettingsController;
use App\Http\Controllers\Merchant\ReviewController as MerchantReviewController;
use App\Http\Controllers\Merchant\StoreController as MerchantStoreSetupController;
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

// Pages ✅ هنا المكان الصحيح
Route::get('/about', [PageController::class, 'about'])->name('pages.about');
Route::get('/contact', [PageController::class, 'contact'])->name('pages.contact');
Route::post('/contact', [PageController::class, 'sendContact'])->name('pages.contact.send');
Route::get('/faq', [PageController::class, 'faq'])->name('pages.faq');

/*
|--------------------------------------------------------------------------
| Cart, Checkout & Wishlist (تحتاج تسجيل دخول)
|--------------------------------------------------------------------------
*/

Route::middleware(['auth'])->group(function () {

    // Cart
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
    Route::put('/cart/{item}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{item}', [CartController::class, 'remove'])->name('cart.remove');
    Route::get('/cart/count', [CartController::class, 'count'])->name('cart.count');

    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
    Route::get('/checkout/success/{order}', [CheckoutController::class, 'success'])->name('checkout.success');
    Route::post('/checkout/apply-coupon', [CheckoutController::class, 'applyCoupon'])->name('checkout.applyCoupon');

    // Profile Edit (Breeze)
    Route::get('/profile', fn() => view('profile.edit'))->name('profile.edit');
});

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

        // Users
        Route::get('users', [AdminUserController::class, 'index'])->name('users.index');
        Route::get('users/{user}', [AdminUserController::class, 'show'])->name('users.show');
        Route::put('users/{user}/toggle', [AdminUserController::class, 'toggleStatus'])->name('users.toggle');
        Route::delete('users/{user}', [AdminUserController::class, 'destroy'])->name('users.destroy');

        // Stores
        Route::get('stores', [AdminStoreController::class, 'index'])->name('stores.index');
        Route::get('stores/{store}', [AdminStoreController::class, 'show'])->name('stores.show');
        Route::put('stores/{store}/approve', [AdminStoreController::class, 'approve'])->name('stores.approve');
        Route::put('stores/{store}/suspend', [AdminStoreController::class, 'suspend'])->name('stores.suspend');
        Route::delete('stores/{store}', [AdminStoreController::class, 'destroy'])->name('stores.destroy');

        // Orders
        Route::get('orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    });

    /*
|--------------------------------------------------------------------------
| Merchant Routes
|--------------------------------------------------------------------------
*/
Route::middleware('role:merchant')->prefix('merchant')->name('merchant.')->group(function () {

    // ========== Store Setup (بدون active.store) ==========
    Route::get('store/create', [MerchantStoreSetupController::class, 'create'])->name('store.create');
    Route::post('store', [MerchantStoreSetupController::class, 'store'])->name('store.store');
    Route::get('store/pending', [MerchantStoreSetupController::class, 'pending'])->name('store.pending');

    // ========== Dashboard ==========
    Route::get('/dashboard', [MerchantDashboard::class, 'index'])->name('dashboard');

    // ========== الباقي (يتطلب متجر نشط) ==========
    Route::middleware('active.store')->group(function () {

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

        // Reviews
        Route::get('reviews', [MerchantReviewController::class, 'index'])->name('reviews.index');
        Route::put('reviews/{review}/approve', [MerchantReviewController::class, 'approve'])->name('reviews.approve');
        Route::put('reviews/{review}/reject', [MerchantReviewController::class, 'reject'])->name('reviews.reject');
        Route::delete('reviews/{review}', [MerchantReviewController::class, 'destroy'])->name('reviews.destroy');

        // Settings
        Route::prefix('settings')->name('settings.')->group(function () {
            Route::get('/', [StoreSettingsController::class, 'index'])->name('index');
            Route::put('/', [StoreSettingsController::class, 'update'])->name('update');

            Route::get('/appearance', [StoreSettingsController::class, 'appearance'])->name('appearance');
    Route::put('/appearance', [StoreSettingsController::class, 'updateAppearance'])->name('appearance.update');

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
});

    /*
    |--------------------------------------------------------------------------
    | Customer Routes
    |--------------------------------------------------------------------------
    */
    Route::middleware('role:customer')->prefix('customer')->name('customer.')->group(function () {

        Route::get('/dashboard', [CustomerDashboard::class, 'index'])->name('dashboard');

        // Wishlist
        Route::get('/wishlist', [WishlistController::class, 'index'])->name('wishlist');
        Route::post('/wishlist/toggle', [WishlistController::class, 'toggle'])->name('wishlist.toggle');
        Route::delete('/wishlist/{wishlist}', [WishlistController::class, 'remove'])->name('wishlist.remove');

        // Orders
        Route::get('/orders', [CustomerOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [CustomerOrderController::class, 'show'])->name('orders.show');
        Route::post('/orders/{order}/cancel', [CustomerOrderController::class, 'cancel'])->name('orders.cancel');

        // Reviews
        Route::get('/reviews', [CustomerReviewController::class, 'index'])->name('reviews.index');
        Route::get('/orders/{order}/review', [CustomerReviewController::class, 'create'])->name('reviews.create');
        Route::post('/orders/{order}/review', [CustomerReviewController::class, 'store'])->name('reviews.store');
        Route::delete('/reviews/{review}', [CustomerReviewController::class, 'destroy'])->name('reviews.destroy');

        // Profile
        Route::get('/profile', [CustomerProfileController::class, 'index'])->name('profile');
        Route::put('/profile', [CustomerProfileController::class, 'update'])->name('profile.update');
        Route::put('/profile/password', [CustomerProfileController::class, 'updatePassword'])->name('profile.password');
    });
});

require __DIR__.'/auth.php';