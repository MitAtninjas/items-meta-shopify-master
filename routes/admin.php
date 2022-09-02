<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\HomeController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\StoreController;
use App\Http\Controllers\Admin\ShippingMethodController;

Route::group([
    'prefix' => 'admin', 'as' => 'admin.',
    'middleware' => ['auth', 'verified', 'role:admin']
], function () {
    Route::get('dashboard', HomeController::class)->name('dashboard');

    Route::post('users/view', [UserController::class, 'view'])->name('users.view');
    Route::post('users/data', [UserController::class, 'data'])->name('users.data');
    Route::match(['GET', 'POST'], 'users/{id}/change-password', [
        UserController::class,
        'changePassword'
    ])->name('users.change-password');
    Route::resource('users', UserController::class);

    Route::post('stores/data', [StoreController::class, 'data'])->name('stores.data');
    Route::resource('stores', StoreController::class);

    Route::get('stores/{store}/shippingMethods', [ShippingMethodController::class, 'edit'])->name('shippingMethods.edit');
    Route::patch('stores/{store}/shippingMethods', [ShippingMethodController::class, 'update'])->name('shippingMethods.update');
});
