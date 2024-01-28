<?php

namespace App\Http\Controllers;

use App\Models\Weather;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Services\WeatherService;


class WeatherController extends Controller
{


    public function index(): \Illuminate\Http\JsonResponse
    {
        $weathers = Weather::with('comments')->get();

        return response()->json([
            'success' => true,
            'data' => $weathers,
        ]);
    }

    // Método para mostrar un registro de weather específico por su ID
    public function show($id): \Illuminate\Http\JsonResponse
    {
        $weather = Weather::find($id);
        if ($weather) {
            return response()->json([
                'success' => true,
                'data' => $weather,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Weather was not found.',
            ], 404);
        }
    }

    // Método para almacenar un nuevo registro de weather
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'city' => 'required|string',
            'temperature' => 'required|numeric',
            'humidity' => 'required|numeric',
        ]);

        $weather = Weather::create($request->all());
        $this->captureActivity($request->user()->id, 'WeatherController', 'store', 'Se ha creado un registro de clima para la ciudad de ' . $request->input('city'));

        return response()->json([
            'success' => true,
            'data' => $weather,
        ], 201);
    }

    // Método para actualizar un registro de weather existente
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'city' => 'required|string',
            'temperature' => 'required|numeric',
            'humidity' => 'required|numeric',
        ]);

        $weather = Weather::find($id);
        if ($weather) {
            $weather->update($request->all());
            return response()->json([
                'success' => true,
                'data' => $weather,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Weather was not found.',
            ], 404);
        }
    }

    // Método para eliminar un registro de weather existente
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $weather = Weather::find($id);
        if ($weather) {
            $weather->delete();
            return response()->json([
                'success' => true,
                'message' => 'Weather deleted success.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Weather was not found.',
            ], 404);
        }
    }
    // Function test API Open Weather Map
    public function testApi(Request $request): \Illuminate\Http\JsonResponse
    {

        $request->validate([
            'city' => 'required|string',
        ]);

        $city = $request->input('city');
        $dataWeather = WeatherService::getInfoByCity($city);

        return response()->json($dataWeather);
    }

    // Custom functions

    public function currentWeatherByCity(Request $request): \Illuminate\Http\JsonResponse
    {
        // Validar la entrada (opcional)
        $request->validate([
            'city' => 'required|string',
        ]);

        // Obtener la ciudad desde la solicitud
        $city = $request->input('city');

        // Obtener la información del weather utilizando el servicio WeatherService
        $dataWeather = WeatherService::getInfoByCity($city);

        // Crear una instancia del modelo Weather con los datos del weather
        $weather = new Weather([
            'city' => $city,
            'temperature' => $dataWeather['main']['temp'],
            'humidity' => $dataWeather['main']['humidity'],
        ]);

        // Retornar la respuesta con los datos del weather, incluyendo la temperatura en Fahrenheit
        return response()->json($weather);
    }

    public function createWeatherByCity(Request $request): \Illuminate\Http\JsonResponse
    {
        // Validar la entrada (opcional)
        $request->validate([
            'city' => 'required|string',
        ]);

        // Obtener la ciudad desde la solicitud
        $city = $request->input('city');

        // Obtener la información del weather utilizando el servicio WeatherService
        $dataWeather = WeatherService::getInfoByCity($city);

        // Crear el modelo Weather con la información obtenida
        $weather = Weather::create([
            'city' => $city,
            'temperature' => $dataWeather['main']['temp'],
            'humidity' => $dataWeather['main']['humidity'],
        ]);

        // Retornar la respuesta con los datos del weather
        return response()->json($weather);
    }
}
