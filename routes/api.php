<?php

use App\Http\Controllers\Api\AdminFlightController;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\FlightSearchController;
use App\Http\Resources\FlightResource;
use App\Models\Flight;
use Illuminate\Support\Facades\Route;

Route::middleware('throttle:10,1')->group(function () {
    Route::post('/register', [AuthController::class, 'register']);
    Route::post('/login', [AuthController::class, 'login']);
});

Route::get('/flights/search', FlightSearchController::class);
Route::get('/flights/featured', [FlightSearchController::class, 'featured'])->name('flights.featured');
Route::get('/flights/{flight}', fn (Flight $flight): FlightResource => FlightResource::make($flight));

Route::middleware('auth:sanctum')->group(function () {
    Route::get('/user', [AuthController::class, 'user']);
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::apiResource('admin/flights', AdminFlightController::class)
        ->middleware('can:manage-flights')
        ->names('admin.flights');
});
