<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Register;
use Illuminate\Database\QueryException;

class RegisterController extends Controller
{
    /**
     * Captura un nuevo registro de actividad.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function captureRegister(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer',
            'controller' => 'required|string',
            'action' => 'required|string',
            'description' => 'required|string',
        ]);

        try {
            // Crear el registro de actividad
            $register = Register::create([
                'user_id' => $request->input('user_id'),
                'controller' => $request->input('controller'),
                'action' => $request->input('action'),
                'description' => $request->input('description'),
            ]);

            return response()->json([
                'success' => true,
                'data' => $register,
            ], 201);
        } catch (QueryException $e) {
            // Manejar errores de la base de datos
            return response()->json([
                'success' => false,
                'message' => 'Error creating activity log: ' . $e->getMessage(),
            ], 500);
        }
    }
}
