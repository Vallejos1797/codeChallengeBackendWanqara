<?php

namespace App\Http\Controllers;

use App\Models\Clima;
use Illuminate\Http\Request;
use GuzzleHttp\Client;
use App\Services\WeatherService;


class WeatherController extends Controller
{


    public function index(): \Illuminate\Http\JsonResponse
    {
        $climas = Clima::all();
        return response()->json([
            'success' => true,
            'data' => $climas,
        ]);
    }

    // Método para mostrar un registro de clima específico por su ID
    public function show($id): \Illuminate\Http\JsonResponse
    {
        $clima = Clima::find($id);
        if ($clima) {
            return response()->json([
                'success' => true,
                'data' => $clima,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró el clima.',
            ], 404);
        }
    }

    // Método para almacenar un nuevo registro de clima
    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'ciudad' => 'required|string',
            'temperatura' => 'required|numeric',
            'humedad' => 'required|numeric',
        ]);

        $clima = Clima::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $clima,
        ], 201);
    }

    // Método para actualizar un registro de clima existente
    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'ciudad' => 'required|string',
            'temperatura' => 'required|numeric',
            'humedad' => 'required|numeric',
        ]);

        $clima = Clima::find($id);
        if ($clima) {
            $clima->update($request->all());
            return response()->json([
                'success' => true,
                'data' => $clima,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró el clima.',
            ], 404);
        }
    }

    // Método para eliminar un registro de clima existente
    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $clima = Clima::find($id);
        if ($clima) {
            $clima->delete();
            return response()->json([
                'success' => true,
                'message' => 'El clima se eliminó correctamente.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró el clima.',
            ], 404);
        }
    }
    // Function test API Open Weather Map
    public function testApi(Request $request): \Illuminate\Http\JsonResponse
    {
        // Validar la entrada (opcional)
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

        // Obtener la información del clima utilizando el servicio WeatherService
        $dataWeather = WeatherService::getInfoByCity($city);

        // Crear una instancia del modelo Clima con los datos del clima
        $clima = new Clima([
            'ciudad' => $city,
            'temperatura' => $dataWeather['main']['temp'],
            'humedad' => $dataWeather['main']['humidity'],
        ]);

        // Retornar la respuesta con los datos del clima, incluyendo la temperatura en Fahrenheit
        return response()->json($clima);
    }

    public function createWeatherByCity(Request $request): \Illuminate\Http\JsonResponse
    {
        // Validar la entrada (opcional)
        $request->validate([
            'city' => 'required|string',
        ]);

        // Obtener la ciudad desde la solicitud
        $city = $request->input('city');

        // Obtener la información del clima utilizando el servicio WeatherService
        $dataWeather = WeatherService::getInfoByCity($city);

        // Crear el modelo Clima con la información obtenida
        $clima = Clima::create([
            'ciudad' => $city,
            'temperatura' => $dataWeather['main']['temp'],
            'humedad' => $dataWeather['main']['humidity'],
        ]);

        // Retornar la respuesta con los datos del clima
        return response()->json($clima);
    }
}
