<?php

use App\Http\Controllers\Admin\AuthController as AdminAuthController;
use App\Http\Controllers\Admin\CategoryController as AdminCategoryController;
use App\Http\Controllers\Admin\ContactMessageController as AdminMessageController;
use App\Http\Controllers\Admin\DashboardController as AdminDashboardController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\PosController as AdminPosController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\SettingController as AdminSettingController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\OrderSuccessController;
use App\Http\Controllers\PageController;
use App\Http\Controllers\ShopController;
use Illuminate\Support\Facades\Route;

/* ==========================================================================
   PUBLIC STORE ROUTES
   ========================================================================== */

Route::get('/', [HomeController::class, 'index'])->name('home');

// Catalog & Products
Route::get('/shop', [ShopController::class, 'index'])->name('shop');
Route::get('/category/{slug}', [ShopController::class, 'category'])->name('category');
Route::get('/product/{slug}', [ShopController::class, 'product'])->name('product');

// Shopping Cart (View & AJAX)
Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');
Route::post('/cart/clear', [CartController::class, 'clear'])->name('cart.clear');

// Checkout & Order Success (WhatsApp Redirection & PDF Receipt)
Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');
Route::get('/order-success/{order_code}', [OrderSuccessController::class, 'show'])->name('order.success');
Route::get('/order-success/{order_code}/pdf', [OrderSuccessController::class, 'downloadPdf'])->name('order.pdf');

// Information & Legal Pages
Route::get('/about', [PageController::class, 'about'])->name('about');
Route::get('/contact', [PageController::class, 'contact'])->name('contact');
Route::post('/contact', [PageController::class, 'contactSubmit'])->name('contact.submit');
Route::get('/privacy-policy', [PageController::class, 'privacy'])->name('privacy');
Route::get('/terms', [PageController::class, 'terms'])->name('terms');
Route::get('/shipping-returns', [PageController::class, 'shippingReturns'])->name('shipping-returns');

/* ==========================================================================
   ADMIN ROUTES (/admin)
   ========================================================================== */

Route::prefix('admin')->name('admin.')->group(function () {
    // Guest Auth
    Route::get('/login', [AdminAuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AdminAuthController::class, 'login'])->name('login.submit');
    Route::post('/logout', [AdminAuthController::class, 'logout'])->name('logout');

    // Protected Admin Panel
    Route::middleware('admin')->group(function () {
        Route::get('/', [AdminDashboardController::class, 'index'])->name('dashboard');
        Route::get('/dashboard', [AdminDashboardController::class, 'index']);

        // Products CRUD
        Route::resource('products', AdminProductController::class);

        // Categories CRUD & Drag and Drop Reordering
        Route::post('/categories/reorder', [AdminCategoryController::class, 'reorder'])->name('categories.reorder');
        Route::resource('categories', AdminCategoryController::class);

        // Orders Management & Point of Sale (POS)
        Route::get('/pos', [AdminPosController::class, 'index'])->name('pos.index');
        Route::post('/pos/store', [AdminPosController::class, 'store'])->name('pos.store');
        Route::get('/orders/create', fn () => redirect()->route('admin.pos.index'))->name('orders.create');
        Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
        Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
        Route::get('/orders/{order}/pdf', [AdminOrderController::class, 'downloadPdf'])->name('orders.pdf');
        Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.updateStatus');

        // Contact Messages
        Route::get('/messages', [AdminMessageController::class, 'index'])->name('messages.index');
        Route::get('/messages/{message}', [AdminMessageController::class, 'show'])->name('messages.show');
        Route::patch('/messages/{message}/status', [AdminMessageController::class, 'updateStatus'])->name('messages.updateStatus');
        Route::delete('/messages/{message}', [AdminMessageController::class, 'destroy'])->name('messages.destroy');

        // Settings
        Route::get('/settings', [AdminSettingController::class, 'index'])->name('settings.index');
        Route::post('/settings', [AdminSettingController::class, 'update'])->name('settings.update');
    });
});
