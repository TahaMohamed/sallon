<?php

use Illuminate\Support\Facades\Route;
use \Modules\Auth\Http\Controllers\LoginController;

Route::post('login', [LoginController::class, 'login']);
Route::post('logout', [LoginController::class, 'logout'])->middleware('auth:sanctum');

