<?php

use App\Http\Controllers\OrdenController;
use Illuminate\Http\Request;
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

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});

Route::get('ordenes/{id}', [OrdenController::class, 'getOrdenDetalle']);
Route::get('orders/filter/{status?}/{group_id?}/{amount?}', [OrdenController::class, 'filterOrders']);
