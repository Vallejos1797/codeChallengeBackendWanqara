<?php

namespace App\Http\Controllers;

use App\Models\Weather;
use App\Models\Register;
use Illuminate\Http\Request;
use GuzzleHttp\Client;


class RegisterController extends Controller
{
    // Método para mostrar todos los registers
    public function index()
    {
        $registers = Register::all();
        return response()->json([
            'success' => true,
            'data' => $registers,
        ]);
    }

    // Método para mostrar un register específico por su ID
    public function show($id)
    {
        $register = Register::find($id);
        if ($register) {
            return response()->json([
                'success' => true,
                'data' => $register,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Register was not found.',
            ], 404);
        }
    }

    // Método para almacenar un nuevo register
    public function store(Request $request)
    {
        $request->validate([
            'type' => 'required|string',
            'description' => 'required|string',
        ]);

        $register = Register::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $register,
        ], 201);
    }

    // Método para actualizar un register existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'type' => 'required|string',
            'description' => 'required|string',
        ]);

        $register = Register::find($id);
        if ($register) {
            $register->update($request->all());
            return response()->json([
                'success' => true,
                'data' => $register,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Register was not found.',
            ], 404);
        }
    }

    // Método para eliminar un register existente
    public function destroy($id)
    {
        $register = Register::find($id);
        if ($register) {
            $register->delete();
            return response()->json([
                'success' => true,
                'message' => 'Register deleted success.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'Register was not found.',
            ], 404);
        }
    }
}
