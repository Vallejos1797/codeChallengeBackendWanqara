<?php

namespace App\Http\Controllers;

use App\Models\Register;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

class RegisterController extends Controller
{
    // Método para mostrar todos los registros
    public function index()
    {
        $registers = Register::all();
        return response()->json($registers);
    }

    // Método para mostrar un registro por su ID
    public function show($id)
    {
        $register = Register::with('comments')->find($id);
        if ($register) {
            return response()->json($register);
        } else {
            return response()->json(['error' => 'Register was not found'], 404);
        }
    }

    // Método para almacenar un nuevo registro
    public function store(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'required|exists:users,id',
            'type' => 'required|string',
            'description' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $register = Register::create($request->all());
        return response()->json($register, 201);
    }

    // Método para actualizar un registro existente
    public function update(Request $request, $id)
    {
        $validator = Validator::make($request->all(), [
            'user_id' => 'exists:users,id',
            'type' => 'string',
            'description' => 'string',
        ]);

        if ($validator->fails()) {
            return response()->json(['error' => $validator->errors()], 422);
        }

        $register = Register::find($id);
        if ($register) {
            $register->update($request->all());
            return response()->json($register);
        } else {
            return response()->json(['error' => 'Register was not found'], 404);
        }
    }

    // Método para eliminar un registro existente
    public function destroy($id)
    {
        $register = Register::find($id);
        if ($register) {
            $register->delete();
            return response()->json(['message' => 'Register deleted']);
        } else {
            return response()->json(['error' => 'Register was not found'], 404);
        }
    }
}
