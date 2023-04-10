<?php

use Illuminate\Http\Request;
use Modules\Vendor\Http\Controllers\ProductController;


Route::middleware('auth:api')->get('/vendor', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::resources([
        'products' => ProductController::class,
    ], ['except' => ['create']]);
});
