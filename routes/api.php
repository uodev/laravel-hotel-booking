<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::prefix('v1')->group(function () {

    Route::apiResource('hotels', \App\Http\Controllers\Api\V1\HotelController::class);
    Route::apiResource('hotels-details', \App\Http\Controllers\Api\V1\HotelReservationDetailsController::class);
    Route::put('hotels-details/{reservationId}', [\App\Http\Controllers\Api\V1\HotelReservationDetailsController::class, 'update']);
});
