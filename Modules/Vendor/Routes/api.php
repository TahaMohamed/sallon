<?php

use Illuminate\Http\Request;
use Modules\Vendor\Http\Controllers\ProductController;
use Modules\Vendor\Http\Controllers\SeatController;


Route::middleware('auth:api')->get('/vendor', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::resources([
        'products' => ProductController::class,
        'seats' => SeatController::class,
    ], ['except' => ['create']]);
});
