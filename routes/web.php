<?php

use App\Http\Controllers\FlightController;
use App\Models\Flight;
use Illuminate\Support\Facades\Route;

Route::view('/', 'home')->name('home');
Route::resource('flights', FlightController::class);
Route::get('/booking/{flight}', function (Flight $flight) {
    return view('booking', compact('flight'));
})->name('bookings.show');
