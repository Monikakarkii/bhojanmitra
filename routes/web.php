<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\MenuItemController;

Route::get('/', function () {
    return view('welcome');
})->name('welcome');
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

    });
