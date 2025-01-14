<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Auth\AuthenticatedSessionController;

Route::get('/', function () {
    return view('welcome');
});
Route::get('/login', [AuthenticatedSessionController::class, 'create'])
            ->name('login');


//create a prefix name admin
Route::prefix('admin')
->middleware('admin')
->group(function () {
        Route::get('/dashboard', function () {

            return view('backend.dashboard');
        })->name('dashboard');

    });
