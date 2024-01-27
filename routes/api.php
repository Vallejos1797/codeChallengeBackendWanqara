<?php

use App\Http\Controllers\WeatherController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\ComentarioController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

Route::middleware('auth:sanctum')->get('/user', function (Request $request) {
    return $request->user();
});


// Test API
Route::get('/weatherByCity', [WeatherController::class, 'getInfoWeatherByCityTest']);

// CRUD Clima
Route::get('/weathers', [WeatherController::class, 'index']);
Route::get('/weathers/{id}', [WeatherController::class, 'show']);
Route::post('/weathers', [WeatherController::class, 'store']);
Route::put('/weathers/{id}', [WeatherController::class, 'update']);
Route::delete('/weathers/{id}', [WeatherController::class, 'destroy']);
Route::get('/weatherByCity', [WeatherController::class, 'getInfoWeatherByCity']);


//CRUD Registro
Route::get('/registers', [RegisterController::class, 'index']);
Route::get('/registers/{id}', [RegisterController::class, 'show']);
Route::post('/registers', [RegisterController::class, 'store']);
Route::put('/registers/{id}', [RegisterController::class, 'update']);
Route::delete('/registers/{id}', [RegisterController::class, 'destroy']);


//CRUD Comentario
Route::get('/comentarios', [ComentarioController::class, 'index']);
Route::get('/comentarios/{id}', [ComentarioController::class, 'show']);
Route::post('/comentarios', [ComentarioController::class, 'store']);
Route::put('/comentarios/{id}', [ComentarioController::class, 'update']);
Route::delete('/comentarios/{id}', [ComentarioController::class, 'destroy']);
