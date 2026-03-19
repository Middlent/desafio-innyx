<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AuthController;
use App\Http\Controllers\ProdutoController;

Route::middleware('auth:sanctum')->group(function () {

    Route::get('/user', function (Request $request) {
        return $request->user();
    });
    Route::post('/logout', [AuthController::class, 'logout']);

    Route::get('/categoria', [ProdutoController::class, 'categories']);
    Route::get('/produto', [ProdutoController::class, 'index']);
    Route::get('/produto/search/{prompt}', [ProdutoController::class, 'search']);
    Route::get('/produto/{produto}', [ProdutoController::class, 'show']);
    Route::post('/produto', [ProdutoController::class, 'store']);
    Route::patch('/produto/{produto}', [ProdutoController::class, 'update']);
    Route::delete('/produto/{produto}', [ProdutoController::class, 'destroy']);

});

Route::post('/register', [AuthController::class, 'register']);
Route::post('/login', [AuthController::class, 'login']);