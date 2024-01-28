<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CommentController;
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


    // Weather Peticiones que son caturadas en la API para tener un traking para auditorias futuas.
    Route::get('/currentWeatherByCity', [WeatherController::class, 'currentWeatherByCity'])->middleware('log.user.activity');
    Route::post('/createWeatherByCity', [WeatherController::class, 'createWeatherByCity'])->middleware('log.user.activity');


// TODO Impletar un control de usaurio por su rol para que estos endpoints lo puedan manejar solo los administradores.
    // BASIC CRUD USER
    Route::get('users', [UserController::class, 'index']);
    Route::get('users/{id}', [UserController::class, 'show']);
    Route::post('users', [UserController::class, 'store']);
    Route::put('users/{id}', [UserController::class, 'update']);
    Route::delete('users/{id}', [UserController::class, 'destroy']);

// BASIC CRUD WEATHER
    Route::get('/weathers', [WeatherController::class, 'index']);
    Route::get('/weathers/{id}', [WeatherController::class, 'show']);
    Route::post('/weathers', [WeatherController::class, 'store']);
    Route::put('/weathers/{id}', [WeatherController::class, 'update']);
    Route::delete('/weathers/{id}', [WeatherController::class, 'destroy']);


// BASIC CRUD  REGISTER
    Route::get('/registers', [RegisterController::class, 'index']);
    Route::get('/registers/{id}', [RegisterController::class, 'show']);
    Route::post('/registers', [RegisterController::class, 'store']);
    Route::put('/registers/{id}', [RegisterController::class, 'update']);
    Route::delete('/registers/{id}', [RegisterController::class, 'destroy']);


// BASIC CRUD COMMENT
    Route::get('/comentarios', [CommentController::class, 'index']);
    Route::get('/comentarios/{id}', [CommentController::class, 'show']);
    Route::post('/comentarios', [CommentController::class, 'store']);
    Route::put('/comentarios/{id}', [CommentController::class, 'update']);
    Route::delete('/comentarios/{id}', [CommentController::class, 'destroy']);

});







