<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AdminController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "web" middleware group. Make something great!
|
*/

Route::get('/', function () {
    return redirect(route("home"));
});

Auth::routes();

Route::get('/home', [App\Http\Controllers\HomeController::class, 'index'])->name('home');

//Admin Routes
Route::group(['prefix' => 'admin'], function () {
    Route::get('/', [AdminController::class, 'index'])->name('admin.index');
    Route::get('/users', [AdminController::class, 'getUser'])->name('admin.users');
    Route::get('/hotels', [AdminController::class, 'getHotels'])->name('admin.hotels');
    Route::get('/hotels/create', [AdminController::class, 'createHotels'])->name('admin.hotels.create');
    Route::post('/hotels/create', [AdminController::class, 'storeHotels'])->name('admin.hotels.create');

    Route::get("/reservation", [AdminController::class, 'getReservation'])->name('admin.reservations');
    Route::get("/reservation/detail/{id}", [AdminController::class, 'getReservationDetail'])->name('admin.reservations.detail');
});

//User Routes
Route::group(['prefix' => 'user'], function () {
    Route::get('/', [App\Http\Controllers\UserController::class, 'index'])->name('user.index');
    Route::post('/reservation', [App\Http\Controllers\UserController::class, 'reservation'])->name('user.reservation');
    Route::get('/reservation-payment', [App\Http\Controllers\UserController::class, 'reservationPayment'])->name('user.reservation.payment');
    Route::post('/reservation-payment', [App\Http\Controllers\UserController::class, 'reservationPaymentCreate'])->name('user.reservation.payment.create');

    Route::get('/reservation/details/{id}', [App\Http\Controllers\UserController::class, 'getReservationDetail'])->name('user.reservation.detail');

    Route::get('/profile', [App\Http\Controllers\UserController::class, 'getProfile'])->name('user.profile');
    Route::get('/profile/credit-carts', [App\Http\Controllers\UserController::class, 'getCreditCarts'])->name('user.profile.credit-carts');
    Route::get('/profile/reservations', [App\Http\Controllers\UserController::class, 'getUserReservations'])->name('user.profile.reservations');
    Route::get('/profile/credit-carts/add', [App\Http\Controllers\UserController::class, 'addCreditCarts'])->name('user.profile.credit-carts.add');
    Route::post('/profile/credit-carts/add', [App\Http\Controllers\UserController::class, 'storeCreditCarts'])->name('user.profile.credit-carts.add');
});

//Hotel Routes
Route::group(['prefix' => 'hotel'], function () {
    Route::get('/', [App\Http\Controllers\HotelController::class, 'index'])->middleware('hotel.auth')->name('hotel.index');
    Route::get('/login', [App\Http\Controllers\HotelController::class, 'login'])->middleware('hotel.guard')->name('hotel.login');
    Route::post('/login', [App\Http\Controllers\HotelController::class, 'getLogin'])->middleware('hotel.guard')->name('hotel.login.post');
    Route::get('/logout', [App\Http\Controllers\HotelController::class, 'logout'])->name('hotel.logout');

    Route::get('/detail', [App\Http\Controllers\HotelController::class, 'getDetail'])->middleware('hotel.auth')->name('hotel.detail.get');
    Route::get('/detail/add', [App\Http\Controllers\HotelController::class, 'addDetail'])->middleware('hotel.auth')->name('hotel.detail.add');
    Route::post('/detail/add', [App\Http\Controllers\HotelController::class, 'createDetail'])->middleware('hotel.auth')->name('hotel.detail.create');

    Route::get('/reservation', [App\Http\Controllers\HotelController::class, 'reservertaion'])->middleware('hotel.auth')->name('hotel.reservation.get');

    Route::get('/reservation/confirm/{id}', [App\Http\Controllers\HotelController::class, 'confirmReservation'])->middleware('hotel.auth')->name('hotel.reservation.confirm');
    Route::get('/reservation/cancel/{id}', [App\Http\Controllers\HotelController::class, 'cancelReservation'])->middleware('hotel.auth')->name('hotel.reservation.cancel');
});
