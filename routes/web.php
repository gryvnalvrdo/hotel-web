<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\HomeController;
use App\Http\Controllers\RoomController;
use App\Http\Controllers\ConferenceController;
use App\Http\Controllers\BookingController;

/*
|--------------------------------------------------------------------------
| Web Routes — Grand Lumina
|--------------------------------------------------------------------------
*/

Route::get('/',            [HomeController::class, 'index'])->name('home');
Route::get('/rooms',       [RoomController::class, 'index'])->name('rooms');
Route::get('/conference',  [ConferenceController::class, 'index'])->name('conference');

// Booking routes
Route::get('/booking',                [BookingController::class, 'index'])->name('booking');
Route::get('/booking/check-availability', [BookingController::class, 'checkAvailability'])->name('booking.check-availability');
Route::post('/booking',               [BookingController::class, 'store'])->name('booking.store');
Route::get('/booking/payment/{id}',   [BookingController::class, 'payment'])->name('booking.payment');
Route::post('/booking/simulate-pay/{id}', [BookingController::class, 'simulatePay'])->name('booking.simulate-pay');
Route::get('/booking/success/{id}',   [BookingController::class, 'success'])->name('booking.success');

// Midtrans webhook (tidak perlu CSRF)
Route::post('/midtrans/callback', [BookingController::class, 'midtransCallback'])
    ->name('midtrans.callback')
    ->withoutMiddleware([\App\Http\Middleware\VerifyCsrfToken::class]);

/*
|--------------------------------------------------------------------------
| Admin Panel Routes — Grand Lumina Hotel
|--------------------------------------------------------------------------
*/
// Authentication Routes (Tanpa Middleware Auth)
Route::get('admin/login', [\App\Http\Controllers\Admin\AuthController::class, 'showLoginForm'])->name('admin.login');
Route::post('admin/login', [\App\Http\Controllers\Admin\AuthController::class, 'login'])->name('admin.login.submit');
Route::post('admin/logout', [\App\Http\Controllers\Admin\AuthController::class, 'logout'])->name('admin.logout');

// Protected Admin Console Routes (Wajib Login)
Route::prefix('admin')->name('admin.')->middleware([\App\Http\Middleware\AdminAuth::class])->group(function () {
    Route::get('/', [\App\Http\Controllers\Admin\DashboardController::class, 'index'])->name('dashboard');
    Route::get('inventory', [\App\Http\Controllers\Admin\InventoryController::class, 'index'])->name('inventory.index');
    Route::post('inventory/quick-update', [\App\Http\Controllers\Admin\InventoryController::class, 'quickUpdate'])->name('inventory.quickUpdate');
    
    // Rooms Management
    Route::resource('rooms', \App\Http\Controllers\Admin\RoomController::class);
    
    // Facilities Management
    Route::get('facilities', [\App\Http\Controllers\Admin\FacilityController::class, 'index'])->name('facilities.index');
    Route::post('facilities/home', [\App\Http\Controllers\Admin\FacilityController::class, 'storeHome'])->name('facilities.storeHome');
    Route::post('facilities/room', [\App\Http\Controllers\Admin\FacilityController::class, 'storeRoom'])->name('facilities.storeRoom');
    Route::delete('facilities/home/{id}', [\App\Http\Controllers\Admin\FacilityController::class, 'destroyHome'])->name('facilities.destroyHome');
    Route::delete('facilities/room/{id}', [\App\Http\Controllers\Admin\FacilityController::class, 'destroyRoom'])->name('facilities.destroyRoom');

    // Bookings Management
    Route::get('bookings', [\App\Http\Controllers\Admin\BookingController::class, 'index'])->name('bookings.index');
    Route::get('bookings/{id}', [\App\Http\Controllers\Admin\BookingController::class, 'show'])->name('bookings.show');
    Route::put('bookings/{id}/status', [\App\Http\Controllers\Admin\BookingController::class, 'updateStatus'])->name('bookings.updateStatus');
    Route::delete('bookings/{id}', [\App\Http\Controllers\Admin\BookingController::class, 'destroy'])->name('bookings.destroy');
});
