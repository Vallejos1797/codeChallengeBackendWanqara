<?php

namespace App\Http\Controllers;

use App\Models\Clima;
use App\Models\Registro;
use Illuminate\Http\Request;
use GuzzleHttp\Client;


class RegisterController extends Controller
{
    // Método para mostrar todos los registros
    public function index()
    {
        $registros = Registro::all();
        return response()->json([
            'success' => true,
            'data' => $registros,
        ]);
    }

    // Método para mostrar un registro específico por su ID
    public function show($id)
    {
        $registro = Registro::find($id);
        if ($registro) {
            return response()->json([
                'success' => true,
                'data' => $registro,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró el registro.',
            ], 404);
        }
    }

    // Método para almacenar un nuevo registro
    public function store(Request $request)
    {
        $request->validate([
            'tipo' => 'required|string',
            'descripcion' => 'required|string',
        ]);

        $registro = Registro::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $registro,
        ], 201);
    }

    // Método para actualizar un registro existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'tipo' => 'required|string',
            'descripcion' => 'required|string',
        ]);

        $registro = Registro::find($id);
        if ($registro) {
            $registro->update($request->all());
            return response()->json([
                'success' => true,
                'data' => $registro,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró el registro.',
            ], 404);
        }
    }

    // Método para eliminar un registro existente
    public function destroy($id)
    {
        $registro = Registro::find($id);
        if ($registro) {
            $registro->delete();
            return response()->json([
                'success' => true,
                'message' => 'El registro se eliminó correctamente.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró el registro.',
            ], 404);
        }
    }
}
