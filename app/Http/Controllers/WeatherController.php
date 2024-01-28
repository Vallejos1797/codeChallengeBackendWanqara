<?php

namespace App\Http\Controllers;
use App\Models\Comment;
use Illuminate\Http\Request;
use App\Models\Weather;
use Carbon\Carbon;
use App\Services\WeatherService;
use Illuminate\Support\Facades\Validator;

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
        $validator = Validator::make($request->all(), [
            'city' => 'required|string',
            'temperature' => 'required|numeric',
            'humidity' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $weather = Weather::create($validator->validated());

        $this->createWeatherComment($weather, $request->input('record'));

        return response()->json([
            'success' => true,
            'data' => $weather,
        ], 201);
    }

    public function update(Request $request, $id): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'city' => 'required|string',
            'temperature' => 'required|numeric',
            'humidity' => 'required|numeric',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $weather = Weather::find($id);
        if (!$weather) {
            return $this->weatherNotFoundResponse();
        }

        $weather->update($validator->validated());
        $this->updateWeatherWithRecord($weather, $request->input('record'));

        return response()->json([
            'success' => true,
            'data' => $weather,
        ]);
    }

    public function destroy($id): \Illuminate\Http\JsonResponse
    {
        $weather = Weather::find($id);
        if (!$weather) {
            return $this->weatherNotFoundResponse();
        }

        $weather->delete();

        return response()->json([
            'success' => true,
            'message' => 'Weather deleted successfully.',
        ]);
    }

    public function testApi(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'city' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $city = $request->input('city');
        $dataWeather = WeatherService::getInfoByCity($city);

        return response()->json($dataWeather);
    }

    public function currentWeatherByCity(Request $request): \Illuminate\Http\JsonResponse
    {
        $validator = Validator::make($request->all(), [
            'city' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

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
        $validator = Validator::make($request->all(), [
            'city' => 'required|string',
            'record' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

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

    private function createWeatherWithRecord($city, $record): void
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

    public function showWeatherWithComments($id): \Illuminate\Http\JsonResponse
    {
        $weather = Weather::with('comments')->find($id);

        if ($weather) {
            return response()->json([
                'success' => true,
                'data' => $weather,
            ]);
        } else {
            return $this->weatherNotFoundResponse();
        }
    }
}
