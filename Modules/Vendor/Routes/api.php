<?php

use Illuminate\Http\Request;
use Modules\Vendor\Http\Controllers\{AttendanceController,
    DepartmentController,
    EmployeeController,
    ProductController,
    SeatController,
    ServiceController};


Route::middleware('auth:api')->get('/vendor', function (Request $request) {
    return $request->user();
});

Route::middleware(['auth:sanctum'])->group(function () {
    Route::resources([
        'departments' => DepartmentController::class,
        'services' => ServiceController::class,
        'products' => ProductController::class,
        'seats' => SeatController::class,
        'employees' => EmployeeController::class,
        'attendances' => AttendanceController::class,
    ], ['except' => ['create']]);

    Route::post('assign_services', [ServiceController::class, 'assignToMe']);
    Route::post('assign_departments', [DepartmentController::class, 'assignToMe']);
});

