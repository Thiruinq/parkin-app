<?php

use App\Http\Controllers\AuthAdminController;
use App\Http\Controllers\AuthOwnerController;
use App\Http\Controllers\AuthUserController;
use App\Http\Controllers\BookingController;
use App\Http\Controllers\CancelledBookingController;
use App\Http\Controllers\ParkingSpotsController;
use App\Http\Controllers\PaymentParkingController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

Route::group(['prefix' => 'auth'], function () {
    Route::post('logout', [AuthUserController::class, 'logout']);
    Route::post('login', [AuthUserController::class, 'login']);
    Route::post('register', [AuthUserController::class, 'register']);

    Route::post('forgot-password', [AuthUserController::class, 'forgotPassword']);
    Route::post('reset-password/{token}', [AuthUserController::class, 'resetPassword']);

    //Parking Owner Routes
    Route::post('adminlogin', [AuthOwnerController::class, 'login']);
    Route::post('adminregister', [AuthOwnerController::class, 'register']);

    //Parking Owner Routes
    Route::post('superadminlogin', [AuthAdminController::class, 'login']);
    Route::post('superadminregister', [AuthAdminController::class, 'register']);

    Route::group(['middleware' => 'auth:sanctum'], function () {
        Route::get('user', [AuthUserController::class, 'user']);
    });
});

// Route::group(['middleware' => 'auth:sanctum'], function() {
//     Route::get('parking-spots', [ParkingSpotsController::class, 'index']);
//     Route::post('parking-spots', [ParkingSpotsController::class, 'create']);
//     Route::put('parking-spots', [ParkingSpotsController::class, 'update']);
//     Route::post('getParkingSpotsByDateTime', [ParkingSpotsController::class, 'getDateTime']);
//     Route::get('booking-detail/{id}', [ParkingSpotsController::class, 'getBookingDetail']);
//   });

Route::group(['middleware' => 'auth:sanctum'], function () {
    Route::post('owner-parking-spots', [ParkingSpotsController::class, 'create']);
    Route::get('owner-parking-spots', [ParkingSpotsController::class, 'ownerindex']);
    Route::put('owner-parking-spots', [ParkingSpotsController::class, 'ownerupdate']);

    Route::get('admin-parking-spots', [ParkingSpotsController::class, 'index']);
    Route::put('admin-parking-spots', [ParkingSpotsController::class, 'adminupdate']);

    Route::get('owner-bookings', [BookingController::class, 'ownerindex']);

});
Route::post('add-booking', [BookingController::class, 'store']);

Route::post('cancel-booking', [CancelledBookingController::class, 'store']);

Route::post('parking-spots', [ParkingSpotsController::class, 'create']);
Route::get('parking-spots', [ParkingSpotsController::class, 'index']);

Route::post('getParkingSpotsByDateTime', [ParkingSpotsController::class, 'getDateTime']);
Route::get('getParkingSpots', [ParkingSpotsController::class, 'getParkingSpots']);
Route::get('booking-detail/{id}', [ParkingSpotsController::class, 'getBookingDetail']);
Route::any('payment-booking', [PaymentParkingController::class, 'getPaymentBooking']);
Route::any('payment-return', [PaymentParkingController::class, 'getPaymentReturn'])->name('booking.payment.show');
Route::any('payment-refund', [PaymentParkingController::class, 'getPaymentRefund']);
//payment-booking

//Admin parking
Route::delete('parking-spots/{id}', [ParkingSpotsController::class, 'destroy']);
Route::put('parking-spots/{id}', [ParkingSpotsController::class, 'update']);
Route::put('parking-spots-edit/{id}', [ParkingSpotsController::class, 'edit']);
Route::get('bookings', [BookingController::class, 'index']);
Route::put('bookings/{id}', [BookingController::class, 'update']);

Route::put('refund-cancel-booking/{id}', [CancelledBookingController::class, 'update']);
Route::get('list-cancel-booking', [CancelledBookingController::class, 'index']);
