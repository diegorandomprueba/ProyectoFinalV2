<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OpinionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;

// Controladores de administración
use App\Http\Controllers\Admin\AdminController;
use App\Http\Controllers\Admin\AdminProductController;
use App\Http\Controllers\Admin\AdminCategoryController;
use App\Http\Controllers\Admin\AdminSubcategoryController;
use App\Http\Controllers\Admin\AdminOrderController;
use App\Http\Controllers\Admin\AdminUserController;

use App\Http\Middleware\IsAdminUser;

// Rutas públicas
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/where', [HomeController::class, 'where'])->name('where');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');
// En routes/web.php
Route::post('/cart/sync', [CartController::class, 'sync'])->name('cart.sync');

// Rutas de autenticación (acceso público)
Route::get('/login', [AuthController::class, 'showLoginForm'])->name('login');
Route::post('/login', [AuthController::class, 'login']);
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);
Route::post('/logout', [AuthController::class, 'logout'])->name('logout');

// Tienda
Route::get('/shop', [ProductController::class, 'index'])->name('shop');
Route::get('/product/{id}', [ProductController::class, 'show'])->name('product.show');

// Carrito
Route::get('/cart', [CartController::class, 'index'])->name('cart');
Route::post('/cart/add', [CartController::class, 'add'])->name('cart.add');
Route::post('/cart/update', [CartController::class, 'update'])->name('cart.update');
Route::post('/cart/remove', [CartController::class, 'remove'])->name('cart.remove');

Route::post('/contact/send', [ContactController::class, 'send'])->name('contact.send');

// Rutas protegidas por autenticación
Route::middleware(['auth'])->group(function () {
    // Checkout
    Route::get('/checkout', [CheckoutController::class, 'index'])->name('checkout');
    Route::post('/checkout/process', [CheckoutController::class, 'process'])->name('checkout.process');
    Route::get('/checkout/success', [CheckoutController::class, 'success'])->name('checkout.success');
    
    // Perfil de usuario
    Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
    Route::get('/profile/edit', [HomeController::class, 'editProfile'])->name('profile.edit');
    Route::put('/profile/update', [HomeController::class, 'updateProfile'])->name('profile.update');
    Route::delete('/profile/destroy', [HomeController::class, 'destroyProfile'])->name('profile.destroy');
    
    // Pedidos
    Route::get('/orders', [HomeController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [HomeController::class, 'showOrder'])->name('orders.show');
    Route::get('/orders/{id}/invoice', [HomeController::class, 'generateInvoice'])->name('orders.invoice');
    Route::post('/orders/{id}/remind', [HomeController::class, 'remindReview'])->name('orders.remind');
    
    // Opiniones (para usuarios autenticados)
    Route::post('/api/opinions', [OpinionController::class, 'storeOpinion']);
});

// API Opiniones (acceso público)
Route::get('/api/opinions/{productId}', [OpinionController::class, 'getOpinions']);
Route::get('/api/ratings/{limit?}', [OpinionController::class, 'getRating']);

// Panel de administración
Route::middleware(['auth'])->prefix('admin')->name('admin.')->group(function () {
    // Dashboard
    Route::get('/', [AdminController::class, 'index'])->name('dashboard');
    
    // Productos
    Route::prefix('products')->name('products.')->group(function () {
        Route::get('/', [AdminProductController::class, 'index'])->name('index');
        Route::get('/create', [AdminProductController::class, 'create'])->name('create');
        Route::post('/', [AdminProductController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminProductController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminProductController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminProductController::class, 'destroy'])->name('destroy');
        Route::post('/{id}/update-price', [AdminProductController::class, 'updatePrice'])->name('updatePrice');
        Route::post('/{id}/update-stock', [AdminProductController::class, 'updateStock'])->name('updateStock');
    });
    
    // Categorías
    Route::prefix('categories')->name('categories.')->group(function () {
        Route::get('/', [AdminCategoryController::class, 'index'])->name('index');
        Route::get('/create', [AdminCategoryController::class, 'create'])->name('create');
        Route::post('/', [AdminCategoryController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminCategoryController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminCategoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminCategoryController::class, 'destroy'])->name('destroy');
    });
    
    // Subcategorías
    Route::prefix('subcategories')->name('subcategories.')->group(function () {
        Route::get('/', [AdminSubcategoryController::class, 'index'])->name('index');
        Route::get('/create', [AdminSubcategoryController::class, 'create'])->name('create');
        Route::post('/', [AdminSubcategoryController::class, 'store'])->name('store');
        Route::get('/{id}/edit', [AdminSubcategoryController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminSubcategoryController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminSubcategoryController::class, 'destroy'])->name('destroy');
        Route::get('/by-category/{categoryId}', [AdminSubcategoryController::class, 'getSubcategoriesByCategory'])->name('byCategory');
    });
    
    // Pedidos
    Route::prefix('orders')->name('orders.')->group(function () {
        Route::get('/', [AdminOrderController::class, 'index'])->name('index');
        Route::get('/{id}', [AdminOrderController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AdminOrderController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminOrderController::class, 'update'])->name('update');
        Route::put('/{id}/status', [AdminOrderController::class, 'updateStatus'])->name('updateStatus');
        Route::delete('/{id}', [AdminOrderController::class, 'destroy'])->name('destroy');
        Route::get('/{id}/invoice', [AdminOrderController::class, 'generateInvoice'])->name('generateInvoice');
    });
    
    // Usuarios
    Route::prefix('users')->name('users.')->group(function () {
        Route::get('/', [AdminUserController::class, 'index'])->name('index');
        Route::get('/create', [AdminUserController::class, 'create'])->name('create');
        Route::post('/', [AdminUserController::class, 'store'])->name('store');
        Route::get('/{id}', [AdminUserController::class, 'show'])->name('show');
        Route::get('/{id}/edit', [AdminUserController::class, 'edit'])->name('edit');
        Route::put('/{id}', [AdminUserController::class, 'update'])->name('update');
        Route::delete('/{id}', [AdminUserController::class, 'destroy'])->name('destroy');
    });
});

// Rutas legales
Route::get('/legal/terms', [HomeController::class, 'terms'])->name('legal.terms');
Route::get('/legal/cookies', [HomeController::class, 'cookies'])->name('legal.cookies');
Route::get('/legal/privacy', [HomeController::class, 'privacy'])->name('legal.privacy');