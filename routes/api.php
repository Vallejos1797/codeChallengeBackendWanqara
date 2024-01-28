<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CommentController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;

// Rutas de autenticación y registro de usuarios
Route::post('login', [AuthController::class, 'authenticate']);
Route::post('register', [AuthController::class, 'register']);

// Rutas protegidas que requieren autenticación JWT
Route::group(['middleware' => 'auth.jwt'], function () {
    // Obtener los detalles del usuario autenticado
    Route::get('user', [AuthController::class, 'getAuthenticatedUser']);

    // Cerrar sesión (invalidar token JWT)
    Route::post('logout', [AuthController::class, 'logout']);

    // CRUD USER
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::post('users', [UserController::class, 'store']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);

    // Weather Routes with additional activity logging middleware
    Route::get('/currentWeatherByCity', [WeatherController::class, 'currentWeatherByCity'])->middleware('log.user.activity');
    Route::post('/createWeatherByCity', [WeatherController::class, 'createWeatherByCity'])->middleware('log.user.activity');
});




// Test API
Route::get('/testApiOWP', [WeatherController::class, 'testApi']);


// Request Basic Weather
Route::get('/weathers', [WeatherController::class, 'index']);
Route::get('/weathers/{id}', [WeatherController::class, 'show']);
Route::post('/weathers', [WeatherController::class, 'store']);
Route::put('/weathers/{id}', [WeatherController::class, 'update']);
Route::delete('/weathers/{id}', [WeatherController::class, 'destroy']);
//Route::get('/createWeatherByCity', [WeatherController::class, 'createInfoWeatherByCity']);
// Request custom Weather
//
//Route::get('/currentWeatherByCity', [WeatherController::class, 'currentWeatherByCity']);
//Route::post('/createWeatherByCity', [WeatherController::class, 'createWeatherByCity']);


//CRUD Register
Route::get('/registers', [RegisterController::class, 'index']);
Route::get('/registers/{id}', [RegisterController::class, 'show']);
Route::post('/registers', [RegisterController::class, 'store']);
Route::put('/registers/{id}', [RegisterController::class, 'update']);
Route::delete('/registers/{id}', [RegisterController::class, 'destroy']);


//CRUD Comment
Route::get('/comentarios', [CommentController::class, 'index']);
Route::get('/comentarios/{id}', [CommentController::class, 'show']);
Route::post('/comentarios', [CommentController::class, 'store']);
Route::put('/comentarios/{id}', [CommentController::class, 'update']);
Route::delete('/comentarios/{id}', [CommentController::class, 'destroy']);
