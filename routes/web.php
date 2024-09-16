<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FormController;
use App\Http\Controllers\Admin\MicrositeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\ImportInvoice\InvoiceController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\ProfileController;
use App\Http\Controllers\Roles\RoleController;
use App\Http\Controllers\Subscription\SubscriptionController;
use App\Http\Controllers\Subscription\SubscriptionPlanController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [MicrositeController::class, 'welcomeIndex'])->name('Welcome');

Route::get('/microsites/{slug}/payment-form', [MicrositeController::class, 'paymentForm'])->name('microsites.paymentForm');

Route::resource('/payments', PaymentController::class);
Route::resource('/subscriptions', SubscriptionController::class);

Route::get('/returnBusiness/{payment}', [PaymentController::class,'returnBusiness'])->name('returnBusiness');
Route::get('/returnSubscription/{subscription}', [SubscriptionController::class,'returnSubscription'])->name('returnSubscription');

Route::group(['middleware' => 'auth', 'verified'], function () {

    Route::get('/dashboard', function () {
        return Inertia::render('Dashboard');
    })->name('dashboard');

    Route::resource('/forms', FormController::class);
    Route::post('/forms/{id}', [FormController::class,'update'])->name('forms.custom_update');
    Route::resource('/roles', RoleController::class);
    Route::resource('/users', UserController::class);
    Route::resource('/categories', CategoryController::class);
    Route::resource('/microsites', MicrositeController::class);
    Route::post('/microsites/{id}', [MicrositeController::class,'update'])->name('microsites.custom_update');
    Route::get('/profile', [ProfileController::class, 'edit'])->name('profile.edit');
    Route::patch('/profile', [ProfileController::class, 'update'])->name('profile.update');
    Route::delete('/profile', [ProfileController::class, 'destroy'])->name('profile.destroy');
    Route::resource('/subscriptionsPlan', SubscriptionPlanController::class);
    Route::resource('/invoices', InvoiceController::class);
    Route::post('/invoices', [InvoiceController::class, 'import'])->name('invoices.import');
    Route::post('/invoices/{invoiceId}/process-payment', [InvoiceController::class, 'processPayment'])->name('invoices.processPayment');

});

require __DIR__.'/auth.php';
