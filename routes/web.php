<?php

use Illuminate\Support\Facades\Route;

use App\Http\Controllers\Admin\WebhooksController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/

Route::get('/', function () {
    return view('landing');
})->name('landing');

//Route::post('/carrier_rate', [CarrierController::class, 'rates'])->name('carrier.index')->middleware('log.route');
Route::post('/webhook/order-created', [WebhooksController::class, 'updateOrderMeta'])->middleware('verify.webhook');
Route::post('/webhook/orders-updated', [WebhooksController::class, 'updateOrderMetaFulfillment'])->middleware('verify.webhook');
Route::post('/webhook/fulfilment-updated', [WebhooksController::class, 'updateOrderMetaFulfillment'])->middleware('verify.webhook');
//Route::post('/webhook/updateDeliveryDate', [WebhooksController::class, 'updateDeliveryDate']);
//Route::post('/webhook/getDateSetting', [WebhooksController::class, 'getDateSettings']);
//Route::get('/webhook/getPackstationSetting', [WebhooksController::class, 'getPackstationSetting']);
//Route::post('/webhook/updatePackstationNo', [WebhooksController::class, 'updatePackstationNo']);

Route::get('/logged-in', function () {
    if (auth()->user()->hasRole('admin')) {
        return redirect('/admin/dashboard');
    } elseif (auth()->user()->hasRole('customer')) {
        return redirect('/customer/dashboard');
    } else {
        return redirect('/');
    }
})->middleware(['auth'])->name('logged-in');

require __DIR__ . '/auth.php';

require __DIR__ . '/admin.php';

require __DIR__ . '/customer.php';

Route::view('ajax','profile');
