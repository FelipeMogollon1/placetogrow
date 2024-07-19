<?php

use App\Http\Controllers\Admin\MicrositeController;
use Illuminate\Support\Facades\Route;

Route::resource('v1/microsite', MicrositeController::class);
