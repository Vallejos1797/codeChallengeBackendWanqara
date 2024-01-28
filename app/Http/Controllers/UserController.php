<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;

class UserController extends Controller
{
    // Método para mostrar todos los usuarios
    public function index()
    {
        $users = User::all();
        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    // Método para mostrar un usuario específico por su ID
    public function show($id)
    {
        $user = User::find($id);
        if ($user) {
            return response()->json([
                'success' => true,
                'data' => $user,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }
    }

    // Método para almacenar un nuevo usuario
    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $user = User::create($request->all());

        return response()->json([
            'success' => true,
            'data' => $user,
        ], 201);
    }

    // Método para actualizar un usuario existente
    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:6',
        ]);

        $user = User::find($id);
        if ($user) {
            $user->update($request->all());
            return response()->json([
                'success' => true,
                'data' => $user,
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }
    }

    // Método para eliminar un usuario existente
    public function destroy($id)
    {
        $user = User::find($id);
        if ($user) {
            $user->delete();
            return response()->json([
                'success' => true,
                'message' => 'User deleted successfully.',
            ]);
        } else {
            return response()->json([
                'success' => false,
                'message' => 'User not found.',
            ], 404);
        }
    }
}
