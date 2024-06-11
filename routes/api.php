<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanKebakaranController;
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

Route::post('/register', [AuthController::class, 'register']);
Route::post('/delete', [AuthController::class, 'deleteUser']);
Route::post('/update', [AuthController::class, 'updateUser']);
Route::post('/login', [AuthController::class, 'login']);
Route::get('/users', [AuthController::class, 'getUsers']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});
Route::post('/laporankebakaran', [LaporanKebakaranController::class, 'store']);
Route::get('/laporan', [LaporanKebakaranController::class, 'index']);
Route::post('/deletelaporan', [LaporanKebakaranController::class, 'delete']);
Route::post('/updatelaporan/{id}', [LaporanKebakaranController::class, 'update']);
Route::get('/laporanid/{id}', [LaporanKebakaranController::class, 'show']);