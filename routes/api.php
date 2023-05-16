<?php

use App\Http\Controllers\TesteApiController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

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


Route::post('/testeApi', [TesteApiController::class, 'testeApi'])->name('teste.testeApi');

Route::post('/testeAdapter', [TesteApiController::class, 'testeAdapter'])->name('teste.testeAdapter');

Route::post('/convertJson', [TesteApiController::class, 'convertJson'])->name('teste.convertJson');
