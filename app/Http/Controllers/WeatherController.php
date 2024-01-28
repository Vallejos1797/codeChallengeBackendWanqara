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

    public function show($id): \Illuminate\Http\JsonResponse
    {
        $weather = Weather::find($id);
        if ($weather) {
            return response()->json([
                'success' => true,
                'data' => $weather,
            ]);
        } else {
            return $this->weatherNotFoundResponse();
        }
    }

    public function store(Request $request): \Illuminate\Http\JsonResponse
    {
        $validatedData = $request->validate([
            'city' => 'required|string',
            'temperature' => 'required|numeric',
            'humidity' => 'required|numeric',
        ]);

        $weather = Weather::create($validatedData);

        $this->createWeatherComment($weather, $request->input('record'));

        return response()->json([
            'success' => true,
            'data' => $weather,
        ], 201);
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $validatedData = $request->validate([
            'city' => 'required|string',
            'temperature' => 'required|numeric',
            'humidity' => 'required|numeric',
        ]);

        $weather = Weather::find($id);
        if ($weather) {
            $weather->update($validatedData);
            $this->updateWeatherWithRecord($weather, $request->input('record'));
            return response()->json([
                'success' => true,
                'data' => $weather,
            ]);
        } else {
            return $this->weatherNotFoundResponse();
        }
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $weather = Weather::find($id);
        if ($weather) {
            $weather->delete();
            return response()->json([
                'success' => true,
                'message' => 'Weather deleted successfully.',
            ]);
        } else {
            return $this->weatherNotFoundResponse();
        }
    }

    public function testApi(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'city' => 'required|string',
        ]);

        $city = $request->input('city');
        $dataWeather = WeatherService::getInfoByCity($city);

        return response()->json($dataWeather);
    }

    public function currentWeatherByCity(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'city' => 'required|string',
        ]);

        $city = $request->input('city');
        $dataWeather = WeatherService::getInfoByCity($city);

        $weather = new Weather([
            'city' => $city,
            'temperature' => $dataWeather['main']['temp'],
            'humidity' => $dataWeather['main']['humidity'],
        ]);

        return response()->json($weather);
    }

    public function createWeatherByCity(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'city' => 'required|string',
            'record' => 'required|string',
        ]);

        $city = $request->input('city');
        $record = $request->input('record');
        $weather = Weather::where('city', $city)->first();

        if ($weather) {
            $this->updateWeatherWithRecord($weather, $record);
        } else {
            $this->createWeatherWithRecord($city, $record);
        }

        return response()->json($weather);
    }

    private function createWeatherWithRecord($city, $record)
    {
        $dataWeather = WeatherService::getInfoByCity($city);
        $weather = Weather::create([
            'city' => $city,
            'temperature' => $dataWeather['main']['temp'],
            'humidity' => $dataWeather['main']['humidity'],
        ]);

        if ($weather) {
            $this->createWeatherComment($weather, $record);
        }
    }

    private function updateWeatherWithRecord($weather, $record)
    {
        $this->createWeatherComment($weather, $record);
    }

    private function createWeatherComment($weather, $record)
    {
        $currentDateTimeString = Carbon::now()->toDateTimeString();
        $commentDescription = "Hoy $currentDateTimeString, {$weather->city}. $record. Información del clima:
            Temperatura Celsius: {$weather->temperature}.
            Humedad: {$weather->humidity}.
            Temperatura Fahrenheit: {$weather->temperature_fahrenheit}";
        $comment = new Comment(['description' => $commentDescription]);
        $weather->comments()->save($comment);
    }

    private function weatherNotFoundResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'Weather not found.',
        ], 404);
    }
}
