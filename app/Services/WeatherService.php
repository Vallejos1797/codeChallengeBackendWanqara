<?php

namespace App\Services;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Support\Facades\Log;

class WeatherService
{
    public static function getInfoByCity($city): array
    {
        try {
            $apiKey = env('WEATHERPERSON_API_KEY');
            $client = new Client();
            $response = $client->get("https://api.openweathermap.org/data/2.5/weather?q={$city}&appid={$apiKey}");
            return json_decode($response->getBody(), true);
        } catch (GuzzleException $e) {
            Log::error('Error al obtener información desde la API publica del clima: ' . $e->getMessage());
            return []; // O puedes devolver un mensaje de error específico si lo prefieres
        }
    }
}
