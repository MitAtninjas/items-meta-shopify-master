<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Customer\HomeController;
use App\Http\Controllers\Customer\StoreController;
use App\Http\Controllers\Customer\ProfileController;
use App\Http\Controllers\Customer\ShippingMethodController;

Route::group(['prefix' => 'customer', 'as' => 'customer.', 'middleware' => ['auth', 'verified', 'role:customer']], function () {
    Route::get('dashboard', HomeController::class)->name('dashboard');
    Route::get('/profile', [ProfileController::class, 'getProfile'])->name('profile.get');
    Route::patch('/profile/{user}', [ProfileController::class, 'updateProfile'])->name('profile.update');

    Route::post('stores/data', [StoreController::class, 'data'])->name('stores.data');
    Route::resource('stores', StoreController::class);

    Route::get('stores/{store}/shippingMethods', [ShippingMethodController::class, 'edit'])->name('shippingMethods.edit');
    Route::patch('stores/{store}/shippingMethods', [ShippingMethodController::class, 'update'])->name('shippingMethods.update');
});
