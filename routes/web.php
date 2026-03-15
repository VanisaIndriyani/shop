<?php

use Illuminate\Support\Facades\Route;
use App\Models\Product;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\AccountController;
use App\Http\Controllers\WishlistController;

use App\Http\Controllers\MessageController;
use App\Http\Controllers\Admin\AdminController as AdminDashboardController;
use App\Http\Controllers\Admin\ProductController as AdminProductController;
use App\Http\Controllers\Admin\OrderController as AdminOrderController;
use App\Http\Controllers\Admin\MessageController as AdminMessageController;
use App\Http\Controllers\Admin\ReportController as AdminReportController;
use App\Http\Controllers\Admin\SettingsController as AdminSettingsController;

Route::get('/', function () {
    $products = Product::latest()->take(4)->get();
    return view('welcome', compact('products'));
});

// Chat Routes
Route::middleware('auth')->group(function () {
    Route::get('/chat', [MessageController::class, 'page'])->name('chat.index');
    Route::post('/chat', [MessageController::class, 'send'])->name('chat.send');
    Route::get('/messages', [MessageController::class, 'index'])->name('messages.index');
    Route::post('/messages', [MessageController::class, 'store'])->name('messages.store');
    Route::post('/messages/mark-admin-read', [MessageController::class, 'markAdminRead'])->name('messages.markAdminRead');
});

Route::get('/shop', [ProductController::class, 'index'])->name('shop.index');
Route::get('/search/products', [ProductController::class, 'search'])->name('shop.search');
Route::get('/shop/{product:slug}', [ProductController::class, 'show'])->name('shop.show');

Route::get('/about', function () {
    return view('about');
})->name('about');

// Custom Authentication Routes
Route::middleware('guest')->group(function () {
    Route::get('/login', [AuthController::class, 'showLogin'])->name('login');
    Route::post('/login', [AuthController::class, 'login']);
    Route::get('/register', [AuthController::class, 'showRegister'])->name('register');
    Route::post('/register', [AuthController::class, 'register']);
});

Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth')->name('logout');

Route::middleware('auth')->group(function () {
    // Cart Routes
    Route::get('/cart', [CartController::class, 'index'])->name('cart.index');
    Route::post('/cart/{product}', [CartController::class, 'addToCart'])->name('cart.add');
    Route::patch('/cart/{cart}', [CartController::class, 'update'])->name('cart.update');
    Route::delete('/cart/{cart}', [CartController::class, 'destroy'])->name('cart.destroy');

    // Checkout Routes
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout.index');
    Route::post('/checkout', [CheckoutController::class, 'store'])->name('checkout.store');

    // Order History Routes
    Route::get('/orders', [OrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [OrderController::class, 'show'])->name('orders.show');

    Route::patch('/account/profile', [AccountController::class, 'updateProfile'])->name('account.profile.update');
    Route::patch('/account/pin', [AccountController::class, 'updatePin'])->name('account.pin.update');

    Route::post('/wishlist/{product}', [WishlistController::class, 'store'])->name('wishlist.store');
    Route::delete('/wishlist/{product}', [WishlistController::class, 'destroy'])->name('wishlist.destroy');
});

Route::get('/account', [AccountController::class, 'index'])->name('account.index');

Route::middleware(['auth', 'admin'])->prefix('admin')->name('admin.')->group(function () {
    Route::get('/dashboard', [AdminDashboardController::class, 'dashboard'])->name('dashboard');
    Route::resource('products', AdminProductController::class)->except(['show']);
    Route::get('/orders', [AdminOrderController::class, 'index'])->name('orders.index');
    Route::get('/orders/{order}', [AdminOrderController::class, 'show'])->name('orders.show');
    Route::patch('/orders/{order}/status', [AdminOrderController::class, 'updateStatus'])->name('orders.status');

    Route::get('/messages', [AdminMessageController::class, 'index'])->name('messages.index');
    Route::get('/messages/{userId}', [AdminMessageController::class, 'show'])->name('messages.show');
    Route::post('/messages/{userId}/reply', [AdminMessageController::class, 'reply'])->name('messages.reply');

    Route::get('/reports', [AdminReportController::class, 'index'])->name('reports.index');
    Route::get('/reports/pdf', [AdminReportController::class, 'exportPdf'])->name('reports.pdf');

    Route::get('/settings', [AdminSettingsController::class, 'edit'])->name('settings.edit');
    Route::patch('/settings', [AdminSettingsController::class, 'update'])->name('settings.update');
});
