<?php

use Illuminate\Http\Request;
use Modules\Dashboard\Http\Controllers\{CenterController,
    CityController,
    CountryController,
    DepartmentController,
    EmployeeController,
    PackageController,
    PackageFeatureController,
    SeatController,
    ServiceController,
    CategoryController,
    ProductController,
    VendorController};
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
        'departments' => DepartmentController::class,
        'categories' => CategoryController::class,
        'products' => ProductController::class,
        'centers' => CenterController::class,
        'vendors' => VendorController::class,
        'employees' => EmployeeController::class,
        'seats' => SeatController::class,
    ], ['except' => ['create']]);

    Route::get('country/{country_id}/cities', [CityController::class, 'index']);
});
