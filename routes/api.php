<?php

use App\Http\Controllers\Api\micrositeController;
use Illuminate\Support\Facades\Route;

Route::resource('v1/microsite', micrositeController::class);
Route::patch('v1/microsite/{microsite}', [micrositeController::class, 'updatePartial']);
