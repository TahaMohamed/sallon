<?php

use Illuminate\Http\Request;
use Modules\Dashboard\Http\Controllers\{CenterController,
    CityController,
    CountryController,
    DepartmentController,
    EmployeeController,
    PackageController,
    PackageFeatureController,
    RoleController,
    SeatController,
    ServiceController,
    CategoryController,
    ProductController,
    AdminController,
    CustomerController,
    SpecialtyController,
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
        'specialties' => SpecialtyController::class,
        'categories' => CategoryController::class,
        'products' => ProductController::class,
        'centers' => CenterController::class,
        'admins' => AdminController::class,
        'vendors' => VendorController::class,
        'employees' => EmployeeController::class,
        'customers' => CustomerController::class,
        'roles' => RoleController::class,
        'seats' => SeatController::class,
    ], ['except' => ['create']]);

    Route::get('categories_list', [CategoryController::class, 'list']);
    Route::get('specialties_list', [SpecialtyController::class, 'list']);
    Route::get('countries_list', [CountryController::class, 'list']);
    Route::get('cities_list', [CityController::class, 'list']);
    Route::get('roles_list', [RoleController::class, 'list']);
    Route::get('permissions_list', [RoleController::class, 'listPermissions']);
    Route::get('country/{country_id}/cities', [CityController::class, 'index']);

});
