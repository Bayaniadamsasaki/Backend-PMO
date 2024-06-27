<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\LaporanKebakaranController;
use App\Http\Controllers\LaporanMedisController;
use App\Http\Controllers\LaporanBencanaController;

Route::post('/register', [AuthController::class, 'register']);
Route::post('/delete', [AuthController::class, 'deleteUser']);
Route::post('/update', [AuthController::class, 'updateUser']);
Route::post('/login', [AuthController::class, 'login']);
Route::post('/logout', [AuthController::class, 'logout'])->middleware('auth:sanctum');
Route::get('/users', [AuthController::class, 'getUsers']);
Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


//Laporan Kebakaran
Route::post('/laporankebakaran', [LaporanKebakaranController::class, 'store']);
Route::get('/laporan', [LaporanKebakaranController::class, 'index']);
Route::post('/deletelaporan/{id}', [LaporanKebakaranController::class, 'delete']);
Route::post('/updatelaporan/{id}', [LaporanKebakaranController::class, 'update']);
Route::get('/laporanid/{id}', [LaporanKebakaranController::class, 'show']);
//Laporan Medis
Route::post('/laporanmedis', [LaporanMedisController::class, 'store']);
Route::get('/laporanmedis', [LaporanMedisController::class, 'index']);
Route::post('/deletelaporanmedis/{id}', [LaporanMedisController::class, 'delete']);
Route::post('/updatelaporanmedis/{id}', [LaporanMedisController::class, 'update']);
Route::get('/laporanmedisid/{id}', [LaporanMedisController::class, 'show']);
//Laporan Bencana Alam
Route::post('/laporanbencana', [LaporanBencanaController::class, 'store']);
Route::get('/laporanbencana', [LaporanBencanaController::class, 'index']);
Route::post('/deletelaporanbencana/{id}', [LaporanBencanaController::class, 'delete']);
Route::post('/updatelaporanbencana/{id}', [LaporanBencanaController::class, 'update']);
Route::get('/laporanbencanaid/{id}', [LaporanBencanaController::class, 'show']);
