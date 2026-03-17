<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\ProdutoController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');


Route::get('/produto', [ProdutoController::class, 'index']);
Route::get('/produto/{produto}', [ProdutoController::class, 'show']);
Route::post('/produto', [ProdutoController::class, 'store']);
Route::patch('/produto/{produto}', [ProdutoController::class, 'update']);
Route::delete('/produto/{produto}', [ProdutoController::class, 'destroy']);

