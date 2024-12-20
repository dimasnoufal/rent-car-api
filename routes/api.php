<?php

use Illuminate\Auth\Middleware\Authenticate;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AccountController;
use App\Http\Controllers\Api\BookingController;
use App\Http\Controllers\Api\CarController;

Route::get('/test-asset', function () {
    return asset('image_cars/676277f4b32d2.png');
});

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/register', [AccountController::class, 'register']);
Route::post('/login', [AccountController::class, 'login']);
Route::post('/reset-password', [AccountController::class, 'resetPassword']);

Route::middleware(['check'])->group(function () {
    /**
     * Car Routinng
     */
    Route::get('/cars', [CarController::class, 'all']);
    Route::get('/cars/available', [CarController::class, 'allAvailableCar']);
    Route::get('/cars/top', [CarController::class, 'topFiveCar']);
    Route::post('/car/add', [CarController::class, 'add']);
    Route::post('/car/{id}', [CarController::class, 'edit']);


    /**
     * Booking Routinng
     */
    Route::get('/booking', [BookingController::class, 'allBooking']);
    Route::get('/booking/{accountId}', [BookingController::class, 'listBookingById']);
    Route::post('/booking/add/{carId}/{accountId}', [BookingController::class, 'booking']);
    Route::post('/booking/edit/{id}', [BookingController::class, 'editStatus']);
});
