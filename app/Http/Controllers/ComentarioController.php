<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Comentario;

class ComentarioController extends Controller
{
    // Método para mostrar todos los comentarios
    public function index()
    {
        $comentarios = Comentario::all();
        return response()->json([
            'success' => true,
            'data' => $comentarios,
        ]);
    }

    // Método para mostrar un comentario específico por su ID
    public function show($id)
    {
        $comentario = Comentario::find($id);
        if ($comentario) {
            return response()->json([
                'success' => true,
                'data' => $comentario,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró el comentario.',
            ], 404);
        }
    }

    // Método para almacenar un nuevo comentario
    public function store(Request $request)
    {
        $request->validate([
            'contenido' => 'required|string',
            // Agrega aquí la validación adicional según tus necesidades
        ]);

        $comentario = Comentario::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $comentario,
        ], 201);
    }

    // Método para actualizar un comentario existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'contenido' => 'required|string',
            // Agrega aquí la validación adicional según tus necesidades
        ]);

        $comentario = Comentario::find($id);
        if ($comentario) {
            $comentario->update($request->all());
            return response()->json([
                'success' => true,
                'data' => $comentario,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró el comentario.',
            ], 404);
        }
    }

    // Método para eliminar un comentario existente
    public function destroy($id)
    {
        $comentario = Comentario::find($id);
        if ($comentario) {
            $comentario->delete();
            return response()->json([
                'success' => true,
                'message' => 'El comentario se eliminó correctamente.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'No se encontró el comentario.',
            ], 404);
        }
    }
}
