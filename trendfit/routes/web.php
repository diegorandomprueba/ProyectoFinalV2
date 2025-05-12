<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\ProductController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CheckoutController;
use App\Http\Controllers\OpinionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\AdminController;
use App\Http\Controllers\ContactController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\AdminCategoryController;
use App\Http\Controllers\AdminSubcategoryController;
use App\Http\Controllers\AdminProductController;

// Rutas públicas
Route::get('/', [HomeController::class, 'index'])->name('home');
Route::get('/about', [HomeController::class, 'about'])->name('about');
Route::get('/where', [HomeController::class, 'where'])->name('where');
Route::get('/contact', [HomeController::class, 'contact'])->name('contact');

// Rutas de autenticación (acceso público)
Route::get('/register', [AuthController::class, 'showRegistrationForm'])->name('register');
Route::post('/register', [AuthController::class, 'register']);

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
    
    // Perfil de usuario - Usar una sola versión del controlador de perfil
    Route::get('/profile', [HomeController::class, 'profile'])->name('profile');
    Route::put('/profile/update', [HomeController::class, 'updateProfile'])->name('profile.update');
    
    // Pedidos
    Route::get('/orders', [HomeController::class, 'orders'])->name('orders');
    Route::get('/orders/{id}', [HomeController::class, 'showOrder'])->name('orders.show');
    
    // Opiniones (para usuarios autenticados)
    Route::post('/api/opinions', [OpinionController::class, 'storeOpinion']);
});

// API Opiniones (acceso público)
Route::get('/api/opinions/{productId}', [OpinionController::class, 'getOpinions']);
Route::get('/api/ratings/{limit?}', [OpinionController::class, 'getRating']);

// Panel de administración
Route::middleware(['auth', 'admin'])->prefix('admin')->group(function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.dashboard');
    
    // Productos
    Route::resource('products', AdminProductController::class);
    
    // Categorías
    Route::resource('categories', AdminCategoryController::class);
    
    // Subcategorías
    Route::resource('subcategories', AdminSubcategoryController::class);
    
    // Pedidos
    Route::get('/orders', [AdminController::class, 'orders'])->name('admin.orders');
    Route::get('/orders/{id}', [AdminController::class, 'showOrder'])->name('admin.orders.show');
    Route::put('/orders/{id}/status', [AdminController::class, 'updateOrderStatus'])->name('admin.orders.status');
    
    // Usuarios
    Route::get('/users', [AdminController::class, 'users'])->name('admin.users');
    Route::get('/users/{id}', [AdminController::class, 'showUser'])->name('admin.users.show');
    Route::put('/users/{id}', [AdminController::class, 'updateUser'])->name('admin.users.update');
});

// Rutas legales
Route::get('/legal/terms', [HomeController::class, 'terms'])->name('legal.terms');
Route::get('/legal/cookies', [HomeController::class, 'cookies'])->name('legal.cookies');
Route::get('/legal/privacy', [HomeController::class, 'privacy'])->name('legal.privacy');

// Dashboard
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth', 'verified'])->name('dashboard');

// Incluir las rutas de autenticación de Breeze
require __DIR__.'/auth.php';