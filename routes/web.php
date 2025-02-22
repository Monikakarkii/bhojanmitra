<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\Auth\PasswordController;

use App\Http\Controllers\MenuController;
use App\Http\Controllers\CartController;

Route::get('/', function () {
    return view('home');
})->name('home');

Route::get('/login', [AuthenticatedSessionController::class, 'create'])
            ->name('login');
            Route::post('login', [AuthenticatedSessionController::class, 'store']);
            Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');


//create a prefix name admin
Route::prefix('admin')
->middleware('admin')
->group(function () {
        Route::get('/dashboard', function () {

            return view('backend.dashboard');
        })->name('dashboard');

        Route::get('site-settings', [SiteSettingController::class, 'index'])->name('site-settings.index');
        Route::post('site-settings', [SiteSettingController::class, 'store'])->name('site-settings.create');

        Route::resource('tables', TableController::class);
        Route::post('/tables/search', [SearchController::class, 'searchTables'])->name('tables.search');

        Route::resource('categories', CategoryController::class);
        Route::post('/categories/search', [CategoryController::class, 'search'])->name('categories.search');

        Route::resource('menu-items', MenuItemController::class);
        Route::post('/menuitems/search', [MenuItemController::class, 'search'])->name('menu-items.search');

        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

        Route::get('verify-email', EmailVerificationPromptController::class)
        ->name('verification.notice');

        Route::get('verify-email/{id}/{hash}', VerifyEmailController::class)
        ->middleware(['signed', 'throttle:6,1'])
        ->name('verification.verify');

        Route::post('email/verification-notification', [EmailVerificationNotificationController::class, 'store'])
        ->middleware('throttle:6,1')
        ->name('verification.send');

        Route::get('confirm-password', [ConfirmablePasswordController::class, 'show'])
        ->name('password.confirm');

        Route::post('confirm-password', [ConfirmablePasswordController::class, 'store']);

        Route::put('password', [PasswordController::class, 'update'])->name('password.update');

    });


    Route::middleware(['user']) // No named parameter
    ->name('menu.')
    ->group(function () {
        Route::get('/home/{tableNumber}/{category?}', [MenuController::class, 'index'])->name('home');
        Route::get('/user-token', [MenuController::class, 'userToken'])->name('user.token');
        Route::get('/menu/{menuSlug}', [MenuController::class, 'show'])->name('show');
        Route::get('/view-all/{categorySlug}', [MenuController::class, 'viewAll'])->name('viewAll');
        Route::post('/cart-add', [CartController::class, 'addToCart'])->name('cart.add');
        Route::post('/cart-update', [CartController::class, 'updateCart'])->name('cart.update');
        Route::post('/cart-remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
        Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');
    });
