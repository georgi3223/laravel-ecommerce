<?php



use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\CustomerController;
use App\Http\Controllers\Api\DashboardController;
use App\Http\Controllers\Api\OrderController;
use App\Http\Controllers\Api\ProductController;
use App\Http\Controllers\Api\ReportController;
use App\Http\Controllers\Api\UserController;
use App\Http\Controllers\Api\CategoryController;
use Illuminate\Support\Facades\Route;

// Routes for authenticated admin users
Route::middleware(['auth:sanctum', 'admin'])->group(function () {
    // User Authentication
    Route::get('/user', [AuthController::class, 'getUser']); // Get authenticated user
    Route::post('/logout', [AuthController::class, 'logout']); // Logout user

    // Resource routes for products, users, customers, and categories
    Route::apiResource('products', ProductController::class); // Product resource
    Route::apiResource('users', UserController::class); // User resource
    Route::apiResource('customers', CustomerController::class); // Customer resource
    Route::apiResource('categories', CategoryController::class)->except('show'); // Category resource, excluding show

    // Additional category and country routes
    Route::get('/categories/tree', [CategoryController::class, 'getAsTree']); // Get categories as a tree structure
    Route::get('/countries', [CustomerController::class, 'countries']); // Get list of countries

    // Order management routes
    Route::get('orders', [OrderController::class, 'index']); // List all orders
    Route::get('orders/statuses', [OrderController::class, 'getStatuses']); // Get order statuses
    Route::post('orders/change-status/{order}/{status}', [OrderController::class, 'changeStatus']); // Change order status
    Route::get('orders/{order}', [OrderController::class, 'view']); // View specific order

    // Dashboard statistics routes
    Route::get('/dashboard/customers-count', [DashboardController::class, 'activeCustomers']); // Active customers count
    Route::get('/dashboard/products-count', [DashboardController::class, 'activeProducts']); // Active products count
    Route::get('/dashboard/orders-count', [DashboardController::class, 'paidOrders']); // Paid orders count
    Route::get('/dashboard/income-amount', [DashboardController::class, 'totalIncome']); // Total income amount
    Route::get('/dashboard/orders-by-country', [DashboardController::class, 'ordersByCountry']); // Orders by country
    Route::get('/dashboard/latest-customers', [DashboardController::class, 'latestCustomers']); // Latest customers
    Route::get('/dashboard/latest-orders', [DashboardController::class, 'latestOrders']); // Latest orders
});

// Report routes
Route::get('/report/orders', [ReportController::class, 'orders']); // Orders report
Route::get('/report/customers', [ReportController::class, 'customers']); // Customers report

// Login Route
Route::post('/login', [AuthController::class, 'login']); // User login
