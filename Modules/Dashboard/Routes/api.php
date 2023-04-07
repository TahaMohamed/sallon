<?php

use Illuminate\Http\Request;
use Modules\Dashboard\Http\Controllers\{
    CityController,
    CountryController,
    PackageFeatureController,
    PackageController,
    ServiceController,
    CategoryController,
    ProductController,
};
Route::middleware('auth:api')->get('/dashboard', function (Request $request) {
    return $request->user();
});
Route::middleware(['auth:sanctum'])->group(function () {
    Route::resources([
        'countries' => CountryController::class,
        'cities' => CityController::class,
        'package_features' => PackageFeatureController::class,
        'packages' => PackageController::class,
        'services' => ServiceController::class,
        'categories' => CategoryController::class,
        'products' => ProductController::class,
    ], ['except' => ['create']]);

    Route::get('country/{country_id}/cities', [CityController::class, 'index']);
});
