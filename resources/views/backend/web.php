<?php

use App\Http\Controllers\Auth\AuthenticatedSessionController;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\CartController;
use App\Http\Controllers\CategoryController;
use App\Http\Controllers\FrontendOrderController;
use App\Http\Controllers\HistoryController;
use App\Http\Controllers\MenuController;
use App\Http\Controllers\MenuItemController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\OrderItemController;
use App\Http\Controllers\SearchController;
use App\Http\Controllers\SiteSettingController;
use App\Http\Controllers\TableController;
use App\Http\Controllers\KitchenController;
use App\Models\MenuItem;
use App\Models\Order;
use App\Models\Table;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Auth\EmailVerificationPromptController;
use App\Http\Controllers\Auth\VerifyEmailController;
use App\Http\Controllers\Auth\EmailVerificationNotificationController;
use App\Http\Controllers\Auth\ConfirmablePasswordController;
use App\Http\Controllers\SalesController;
use App\Http\Controllers\Auth\PasswordController;

use function App\Helpers\getSiteSetting;

Route::get('/', function () {
    return view('home');
})->name('home');
Route::post('/logout', [MenuController::class, 'logout'])->name('logout');

Route::middleware('user')
    ->name('menu.')
    ->group(function () {
        Route::get('/home/{tableNumber}/{category?}', [MenuController::class, 'index'])->name('home');
        // More specific route comes first
        Route::get('/view-all/{categorySlug}', [MenuController::class, 'viewAll'])->name('viewAll');
        Route::get('/menu/{menuSlug}', [MenuController::class, 'show'])->name('show');
        Route::post('/cart-add', [CartController::class, 'addToCart'])->name('cart.add');
        Route::post('/cart-update', [CartController::class, 'updateCart'])->name('cart.update');
        Route::post('/cart-remove', [CartController::class, 'removeFromCart'])->name('cart.remove');
        Route::get('/cart', [CartController::class, 'viewCart'])->name('cart.view');
        Route::get('/order-history', [HistoryController::class, 'index'])->name('order.history');
        Route::post('/order/store', [FrontendOrderController::class, 'store'])->name('order.store');
        Route::get('/user-token', [MenuController::class, 'userToken'])->name('user.token');



    });


        Route::get('/login', [AuthenticatedSessionController::class, 'create'])
            ->name('login');

        Route::post('login', [AuthenticatedSessionController::class, 'store']);
        Route::post('/logout', [AuthenticatedSessionController::class, 'destroy'])->name('logout');




Route::prefix('admin')
->middleware('role:admin')
->group(function () {
        Route::get('/dashboard', function () {
            $menuItemCount = MenuItem::count(); // Get the count directly from the database
            $tablesCount = Table::count();
            $orders = Order::where('order_status', 'pending')->latest()->take(5)->get();
            return view('backend.dashboard', compact('menuItemCount', 'tablesCount', 'orders'));
        })->name('dashboard');
        Route::get('site-settings', [SiteSettingController::class, 'index'])->name('site-settings.index');
        Route::post('site-settings', [SiteSettingController::class, 'store'])->name('site-settings.create');
        Route::resource('tables', TableController::class);
        Route::post('/tables/search', [SearchController::class, 'searchTables'])->name('tables.search');
        Route::resource('categories', CategoryController::class);
        Route::post('/categories/search', [CategoryController::class, 'search'])->name('categories.search');
        Route::resource('menu-items', MenuItemController::class);
        Route::post('/menuitems/search', [MenuItemController::class, 'search'])->name('menu-items.search');
        Route::resource('orders', OrderController::class)->except(['show']);
        Route::post('order-items', [OrderItemController::class, 'store'])->name('order-items.store');
        Route::get('/sales', [SalesController::class, 'index'])->name('sales.index');
        Route::get('/sales/download', [SalesController::class, 'download'])->name('sales.download');
        Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
        Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
        Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');


        //Profile controller for admin and kitchen
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



    //route for kitchen with middleware Kitchen_or_admin routes
    Route::prefix('kitchen')
    ->middleware('kitchen_or_admin') // Ensure this middleware checks roles properly
    ->group(function () {
        Route::get('/dashboard', [KitchenController::class, 'dashboard'])->name('kitchen-dashboard');
        Route::put('/order/change-status/{id}/{status}', [KitchenController::class, 'changeStatus'])->name('order.changeStatus');
        //kitchen history
        Route::get('/history', [KitchenController::class, 'history'])->name('kitchen.history');




    });



