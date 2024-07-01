<?php

use App\Http\Controllers\Api\categoryController;
use App\Http\Controllers\Api\micrositeController;
use App\Http\Controllers\Api\userController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Roles\RoleController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [micrositeController::class, 'welcomeIndex'])->name('Welcome');


Route::group(['middleware' => 'auth', 'verified'], function () {

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('/roles',RoleController::class);
    Route::resource('/users', userController::class);
    Route::resource('/categories', CategoryController::class);
    Route::resource('/microsites', micrositeController::class);
    Route::post('/microsites/{id}',[micrositeController::class,'update'])->name('microsites.update');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');

});

require __DIR__.'/auth.php';
