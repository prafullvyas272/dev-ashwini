<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\DeviceController;
use App\Http\Controllers\LeasingController;
use App\Http\Middleware\AuthMiddleware;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::post('/device/register', [DeviceController::class, 'registerDevice']);

Route::middleware([AuthMiddleware::class])->group(function () {
    Route::get('/device/info/{id}', [DeviceController::class, 'getDeviceInfo']);
    Route::post('/leasing/update/{id}', [LeasingController::class, 'updateLeasing']);
});
