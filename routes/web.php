<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FormController;
use App\Http\Controllers\Admin\MicrositeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Roles\RoleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [MicrositeController::class, 'welcomeIndex'])->name('Welcome');

Route::get('/microsites/{slug}/payment-form', [MicrositeController::class, 'paymentForm'])->name('microsites.paymentForm');

Route::resource('/payments', PaymentController::class);

Route::group(['middleware' => 'auth', 'verified'], function () {

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('/forms', FormController::class);
    Route::resource('/roles', RoleController::class);
    Route::resource('/users', UserController::class);
    Route::resource('/categories', CategoryController::class);
    Route::resource('/microsites', MicrositeController::class);
    Route::post('/microsites/{id}', [MicrositeController::class,'update'])->name('microsites.custom_update');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';
