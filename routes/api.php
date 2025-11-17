<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\CnhController;

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

Route::prefix('v2/senatran')->group(function () {
    Route::get('/buscar-cnh', [CnhController::class, 'buscarCnh']);
    Route::post('/salvar-cnh', [CnhController::class, 'salvarCnh']);
    Route::post('/validar-cnh', [CnhController::class, 'validarCnh']);
});
