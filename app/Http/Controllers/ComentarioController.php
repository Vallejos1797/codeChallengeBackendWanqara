<?php

namespace App\Http\Controllers;

use App\Models\Weather;
use Illuminate\Http\Request;
use App\Models\Comment;

class ComentarioController extends Controller
{
    // Método para mostrar todos los comentarios
    public function index()
    {
        $comentarios = Comment::all();
        return response()->json([
            'success' => true,
            'data' => $comentarios,
        ]);
    }

    // Método para mostrar un comentario específico por su ID
    public function show($id)
    {
        $comentario = Comment::find($id);
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
            'commentable_id' => 'required'
        ]);

        // Obtén el valor de 'commentable_id' del cuerpo JSON de la solicitud
        $commentableId = $request->input('commentable_id');

        // Crea un nuevo array con los datos requeridos para crear el comentario
        $data = [
            'contenido' => $request->input('contenido'),
            'commentable_id' => $commentableId,
            'commentable_type' => 1
        ];

        // Crea el comentario utilizando los datos preparados
        $comentario = Comment::create($data);

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

        $comentario = Comment::find($id);
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
        $comentario = Comment::find($id);
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
