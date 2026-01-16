<?php

use App\Http\Controllers\Api\ImageController;
use Illuminate\Support\Facades\Route;

Route::controller(ImageController::class)->group(function () {
    Route::get('/images', 'index');
    Route::post('/images', 'store');
});
