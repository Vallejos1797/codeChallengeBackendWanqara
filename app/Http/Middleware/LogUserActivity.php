<?php

namespace App\Http\Middleware;

use App\Models\Comment;
use App\Models\Register;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LogUserActivity
{
    public function handle(Request $request, Closure $next)
    {
        try {
            // Procesar la solicitud
            $response = $next($request);

            // Obtener el método y la ruta de la solicitud
            $method = $request->method();
            $path = $request->path();

            // Obtener el usuario autenticado
            $user = Auth::user();

            // Registrar la actividad del usuario en el modelo Register
            $activity = Register::create([
                'type' => $method, // Tipo de actividad
                'description' => "User accessed $path", // Descripción de la actividad
                'user_id' => $user->id,
                'status' => 'ok', // Estado de la solicitud
            ]);

            // Crear un comentario asociado a la actividad registrada
            if ($activity) {
                $commentDescription = "Ok, Request by {$user->name} on $path using $method method";
                $comment = new Comment([
                    'description' => $commentDescription,
                ]);
                $activity->comments()->save($comment);
            }

            return $response; // Retornar la respuesta de la solicitud
        } catch (\Exception $e) {
            // Si hay un error, registrar la actividad con estado 'error'
            $method = $request->method();
            $path = $request->path();
            $user = Auth::user();

            Register::create([
                'type' => $method,
                'description' => "Error accessing $path: " . $e->getMessage(),
                'user_id' => $user->id,
                'status' => 'error',
            ]);


            throw $e; // Relanzar la excepción para que sea manejada por el controlador de errores global
        }
    }
}
