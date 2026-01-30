<?php

use App\Http\Controllers\Api\V1\VehicleController;
use App\Http\Controllers\Api\V1\VehicleTrackingController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/vehicles/{vehicle}/location', [VehicleTrackingController::class, 'update']);
Route::get('/vehicles/nearby', [VehicleController::class, 'nearby']);
