<?php


use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\ProfileController;
use Illuminate\Support\Facades\Route;


// Public Routes (Guest or Verified Users)
Route::middleware(['guestOrVerified'])->group(function () {
   
Route::get('/', [ProductController::class, 'index'])->name('home'); // Home page
Route::get('/category/{category:slug}', [ProductController::class, 'byCategory'])->name('byCategory'); // Products by category
Route::get('/product/{product:slug}', [ProductController::class, 'view'])->name('product.view'); // View product details

// Cart Routes
Route::prefix('/cart')->name('cart.')->group(function () {
    Route::get('/', [CartController::class, 'index'])->name('index'); // View cart
    Route::post('/add/{product:slug}', [CartController::class, 'add'])->name('add'); // Add product to cart
    Route::post('/remove/{product:slug}', [CartController::class, 'remove'])->name('remove'); // Remove product from cart
    Route::post('/update-quantity/{product:slug}', [CartController::class, 'updateQuantity'])->name('update-quantity'); // Update product quantity
}); 
});

// Authenticated Routes (Authenticated and Verified Users)
Route::middleware(['auth', 'verified'])->group(function() {
    // Profile Routes
    Route::get('/profile', [ProfileController::class, 'view'])->name('profile'); // View profile
    Route::post('/profile', [ProfileController::class, 'store'])->name('profile.update'); // Update profile
    Route::post('/profile/password-update', [ProfileController::class, 'passwordUpdate'])->name('profile_password.update'); // Update password

    // Checkout Routes
    Route::post('/checkout', [CheckoutController::class, 'checkout'])->name('cart.checkout'); // Initiate checkout
    Route::post('/checkout/{order}', [CheckoutController::class, 'checkoutOrder'])->name('cart.checkout-order'); // Checkout for specific order
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success'); // Successful checkout page
    Route::get('/checkout/failure', [CheckoutController::class, 'failure'])->name('checkout.failure'); // Failed checkout page

    // Order Routes
    Route::get('/orders', [OrderController::class, 'index'])->name('order.index'); // View all orders
    Route::get('/orders/{order}', [OrderController::class, 'view'])->name('order.view'); // View specific order
});

// Webhook for Stripe
Route::post('/webhook/stripe', [CheckoutController::class, 'webhook']);

// Include Authentication Routes
require __DIR__ . '/auth.php';
