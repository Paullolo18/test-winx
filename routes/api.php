<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\EmpresaController;
use App\Http\Controllers\CSVUploadController;
use App\Http\Controllers\ColaboradorController;

Route::get('/', function () {
    return response()->json(['status' => 'API test Winx Online!']);
});


Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);

Route::middleware('auth:sanctum')->group(function () {
    Route::post('/logout', [AuthController::class, 'logout']);
    Route::post('/empresas', [EmpresaController::class, 'store']);
    Route::get('/empresas', [EmpresaController::class, 'show']);
    Route::post('/upload-csv', [CSVUploadController::class, 'upload']);
    
    Route::get('/colaboradores', [ColaboradorController::class, 'index']);
    Route::get('/colaboradores/{id}', [ColaboradorController::class, 'show']);

    Route::get('/empresas', [EmpresaController::class, 'index']);
    Route::post('/empresas', [EmpresaController::class, 'store']);
    Route::put('/empresas/{id}', [EmpresaController::class, 'update']);
    Route::delete('/empresas/{id}', [EmpresaController::class, 'destroy']);
});
