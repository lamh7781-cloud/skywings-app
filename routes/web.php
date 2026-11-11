<?php

use App\Http\Controllers\FlightController;
use App\Http\Controllers\SessionAuthController;
use App\Models\Flight;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::get('/login', [SessionAuthController::class, 'create'])->name('login');
Route::post('/login', [SessionAuthController::class, 'store'])->middleware('throttle:5,1')->name('login.store');
Route::post('/logout', [SessionAuthController::class, 'destroy'])->middleware('auth')->name('logout');
Route::resource('flights', FlightController::class)->middleware(['auth', 'can:manage-flights']);
Route::get('/booking/{flight}', function (Flight $flight) {
    return view('booking', compact('flight'));
})->name('bookings.show');
