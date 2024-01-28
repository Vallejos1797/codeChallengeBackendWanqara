<?php
use App\Http\Controllers\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\WeatherController;
use App\Http\Controllers\RegisterController;
use App\Http\Controllers\CommentController;
use Illuminate\Support\Facades\Route;

// Authentication and user registration routes
Route::post('login', [AuthController::class, 'authenticate']);
Route::post('register', [AuthController::class, 'register']);

// Protected routes that require JWT authentication
Route::group(['middleware' => 'auth.jwt'], function () {

    // Get details of the authenticated user
    Route::get('user', [AuthController::class, 'getAuthenticatedUser']);

    // Logout (invalidate JWT token)
    Route::post('logout', [AuthController::class, 'logout']);

    // Logging API requests
    Route::get('/logsAPI', [CommentController::class, 'requestsLog']);

    // Weather API endpoints with activity logging middleware
    Route::middleware('log.user.activity')->group(function () {
        Route::get('/currentWeatherByCity', [WeatherController::class, 'currentWeatherByCity']);
        Route::post('/createWeatherByCity', [WeatherController::class, 'createWeatherByCity']);
        Route::get('/getRecordsByCity/{id}', [WeatherController::class, 'showWeatherWithComments']);
    });

    // User CRUD operations
    Route::resource('users', UserController::class)->except(['create', 'edit']);

    // Weather CRUD operations
    Route::resource('weathers', WeatherController::class)->except(['create', 'edit']);

    // Register CRUD operations
    Route::resource('registers', RegisterController::class)->except(['create', 'edit']);

    // Comment CRUD operations
    Route::resource('comments', CommentController::class)->except(['create', 'edit']);
});
