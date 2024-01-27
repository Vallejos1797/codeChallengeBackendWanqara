<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use GuzzleHttp\Client;


class WeatherController extends Controller
{
    public function getInfoWeatherByCity(Request $request): \Illuminate\Http\JsonResponse
    {
        $apiKey = env('WEATHERPERSON_API_KEY');
        $ciudad = $request->input('city');
        $client = new Client();
        $response = $client->get("http://api.openweathermap.org/data/2.5/weather?q={$ciudad}&appid={$apiKey}");
        $dataWeather = json_decode($response->getBody(), true);
        return response()->json($dataWeather);
    }
}
