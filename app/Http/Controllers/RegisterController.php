<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Register;

class RegisterController extends Controller
{
    public function captureRegister(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'user_id' => 'required|integer',
            'controller' => 'required|string',
            'action' => 'required|string',
            'description' => 'required|string',
        ]);

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
    }
}
