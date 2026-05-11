<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\API\AuthController;
use App\Http\Controllers\API\VendorController;
use App\Http\Controllers\API\CategoryController;
use App\Http\Controllers\API\ProductController;
use App\Http\Controllers\API\CartController;
use App\Http\Controllers\API\OrderController;
use App\Http\Controllers\API\SearchController;
use App\Http\Controllers\API\AnalyticsController;
use App\Http\Controllers\API\ChatbotController;

Route::prefix('v1')->group(function () {
    //Route::prefix('auth')->group(function () {
        Route::post('register', [AuthController::class, 'register']);
        Route::post('login', [AuthController::class, 'login'])->middleware('throttle:3,1');

        Route::middleware('auth:sanctum')->group(function () {
            // assign user to cart
            Route::post('/assign-user', [CartController::class, 'assignUser']);
            
            Route::post('logout', [AuthController::class, 'logout']);
            Route::get('me', [AuthController::class, 'me']);

            // Vendor Api
            Route::post('/vendors', [VendorController::class, 'create']);
            Route::get('/vendors', [VendorController::class, 'list']);
            Route::get('/vendors/{id}', [VendorController::class, 'details']);
            Route::patch('/vendors', [VendorController::class, 'updateProfile']);

            // Create Product
            Route::post('/products', [ProductController::class, 'create']);

            // Category
            Route::get('/category', [CategoryController::class, 'index']);
            Route::post('/category', [CategoryController::class, 'create']);
            Route::put('/category/{id}', [CategoryController::class, 'update']);
            Route::delete('/category/{id}', [CategoryController::class, 'destroy']);

            // Order
            Route::get('/checkout', [OrderController::class, 'checkout']);
            Route::post('/orders', [OrderController::class, 'placeOrder']);
            Route::get('/order/{id}', [OrderController::class, 'orderDetail']);
            Route::get('/orders', [OrderController::class, 'history']);
            
        });
    //});

    // Category Api
    Route::get('/category', [CategoryController::class, 'index']);
    Route::get('/category/{id}', [CategoryController::class, 'show']);

    // Product Api
    Route::get('/products', [ProductController::class, 'list']);
    Route::get('/products/{slug}', [ProductController::class, 'details']);
    Route::get('/products/vendors/{vendorId}', [ProductController::class, 'vendorProducts']);

    // Cart Api
    Route::prefix('cart')->group(function () {
        Route::get('/', [CartController::class, 'getCart']);
        Route::post('/add', [CartController::class, 'add']);
        Route::put('/update', [CartController::class, 'update']);
        Route::delete('/remove/{id}', [CartController::class, 'remove']);
    });

    // FastAPI search
    Route::get('/search', [SearchController::class, 'index']);

    // FastAPI Analytics
    Route::prefix('analytics')->group(function() {
        Route::get('/top-products', [AnalyticsController::class, 'topProducts']);
        Route::get('/basic-stats', [AnalyticsController::class, 'basicStats']);
    });

    // FastAPI chatbot
    Route::post('/chat', [ChatbotController::class, 'send']);
});