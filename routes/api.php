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

Route::post('orden/{id}', [OrdenController::class, 'getOrdenDetalle']);//Requerimiento A y B

Route::get('ordenes/filtro/{status?}/{group_id?}/{amount?}', [OrdenController::class, 'filtrarOrdenes']);//Requerimiento C

Route::get('ordenes/total', [OrdenController::class, 'totalOrdenes']); //Requerimiento D

Route::post('ordenes/guardar/{id}', [OrdenController::class, 'guardarTodasOrdenes']);//Requerimiento E

Route::delete('ordenes/eliminar/{id}', [OrdenController::class, 'eliminarOrden']);//Requerimiento F