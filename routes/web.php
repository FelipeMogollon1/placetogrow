<?php

use App\Http\Controllers\Admin\CategoryController;
use App\Http\Controllers\Admin\FormController;
use App\Http\Controllers\Admin\MicrositeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Dashboard\DashboardController;
use App\Http\Controllers\Invoice\InvoiceController;
use App\Http\Controllers\Invoice\InvoiceUploadController;
use App\Http\Controllers\Payment\PaymentController;
use App\Http\Controllers\Profile\ProfileController;
use App\Http\Controllers\Roles\RoleController;
use App\Http\Controllers\Subscription\SubscriptionController;
use App\Http\Controllers\SubscriptionPayment\SubscriptionPaymentController;
use App\Http\Controllers\SubscriptionPlan\SubscriptionPlanController;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

Route::get('/', [MicrositeController::class, 'welcomeIndex'])->name('Welcome');
Route::get('/microsites/{slug}/payment-form', [MicrositeController::class, 'paymentForm'])->name('microsites.paymentForm');
Route::resource('/payments', PaymentController::class);
Route::get('/returnBusiness/{payment}', [PaymentController::class,'returnBusiness'])->name('returnBusiness');
Route::get('/returnSubscription/{subscription}', [SubscriptionController::class,'returnSubscription'])->name('returnSubscription');
Route::resource('/subscriptions', SubscriptionController::class);
Route::get('/invoices/download-template', [InvoiceController::class, 'downloadTemplate'])
    ->name('invoices.download-template');

Route::group(['middleware' => 'auth', 'verified'], function () {

    Route::resource('dashboard', DashboardController::class);
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
    Route::resource('/subscriptionPayments', SubscriptionPaymentController::class);
    Route::resource('/invoices', InvoiceController::class);
    Route::resource('/invoicesUpload', InvoiceUploadController::class);
    Route::post('/invoices', [InvoiceController::class, 'import'])->name('invoices.import');
    Route::post('/invoices/{invoiceId}/process-payment', [InvoiceController::class, 'processPayment'])->name('invoices.processPayment');
    Route::get('/returnInvoice/{invoice}', [InvoiceController::class,'returnInvoice'])->name('returnInvoice');

});

require __DIR__.'/auth.php';
