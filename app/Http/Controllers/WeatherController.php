<?php

namespace App\Http\Controllers;

use App\Models\Comment;
use App\Models\Weather;
use Carbon\Carbon;
use Illuminate\Http\Request;
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
            'record' => 'required|string',
        ]);

        // Obtener la ciudad desde la solicitud
        $city = $request->input('city');
        $record = $request->input('record');

        // Buscar el modelo Weather existente por la ciudad
        $weather = Weather::where('city', $city)->first();

        if ($weather) {
            // Si se encuentra el modelo Weather, actualizar sus notas
            $currentDateTimeString = Carbon::now()->toDateTimeString();

            // Construir la nueva descripción de las notas del clima
            $newCommentDescription = "Hoy $currentDateTimeString, $city. $record.
             Información del clima:
        Temperatura Celsius: {$weather->temperature}.
        Humedad: {$weather->humidity}.
        Temperatura Fahrenheit: {$weather->temperature_fahrenheit}
        ";
            $comment = new Comment([
                'description' => $newCommentDescription,
            ]);

            // Actualizar la descripción de las notas del clima
            $weather->comments()->save($comment);

        } else {
            // Si no se encuentra el modelo Weather, crear uno nuevo
            $dataWeather = WeatherService::getInfoByCity($city);

            // Crear el modelo Weather con la información obtenida
            $weather = Weather::create([
                'city' => $city,
                'temperature' => $dataWeather['main']['temp'],
                'humidity' => $dataWeather['main']['humidity'],
            ]);

            if ($weather) {
                // Obtener la fecha y hora actual
                $currentDateTimeString = Carbon::now()->toDateTimeString();

                // Construir la descripción de las notas del clima
                $commentDescription = "Hoy $currentDateTimeString, $city. $record.
            Información del clima:
            Temperatura Celsius: {$weather->temperature}.
            Humedad: {$weather->humidity}.
            Temperatura Fahrenheit: {$weather->temperature_fahrenheit}
            ";

                // Crear un nuevo comentario asociado al modelo Weather con la descripción construida
                $comment = new Comment([
                    'description' => $commentDescription,
                ]);
                $weather->comments()->save($comment);
            }
        }

        // Retornar la respuesta con los datos del weather
        return response()->json($weather);
    }

}
