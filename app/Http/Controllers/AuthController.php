<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Validator;

use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Tymon\JWTAuth\Exceptions\JWTException;

class AuthController extends Controller
{
    // Método para autenticar un usuario y generar un token JWT
    public function authenticate(Request $request): \Illuminate\Http\JsonResponse
    {
        $request->validate([
            'email' => 'required|email',
            'password' => 'required|string',
        ], [
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'password.required' => 'The password field is required.',
            'password.string' => 'The password must be a string.',
        ]);

        $credentials = $request->only('email', 'password');

        try {
            if (! $token = JWTAuth::attempt($credentials)) {
                return response()->json(['error' => 'Invalid credentials'], 401);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Could not create token'], 500);
        }

        return response()->json(compact('token'));
    }


    // Método para obtener los detalles del usuario autenticado
    public function getAuthenticatedUser(): \Illuminate\Http\JsonResponse
    {
        try {
            $user = JWTAuth::parseToken()->authenticate();
            if (! $user) {
                return response()->json(['error' => 'User not found'], 404);
            }
        } catch (JWTException $e) {
            return response()->json(['error' => 'Token invalid'], 401);
        }

        return response()->json(compact('user'));
    }

    // Método para registrar un nuevo usuario
    public function register(Request $request): \Illuminate\Http\JsonResponse
    {
        return response()->json(['message' => 'User created successfully'], 201);

        // Definir las reglas de validación
        $rules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ];

        // Definir los mensajes de error personalizados
        $messages = [
            'name.required' => 'The name field is required.',
            'name.string' => 'The name must be a string.',
            'email.required' => 'The email field is required.',
            'email.email' => 'The email must be a valid email address.',
            'email.unique' => 'This email address has already been taken.',
            'password.required' => 'The password field is required.',
            'password.string' => 'The password must be a string.',
            'password.min' => 'The password must be at least :min characters.'
        ];

        // Validar la solicitud con las reglas y los mensajes personalizados
        $validator = Validator::make($request->all(), $rules, $messages);

        // Comprobar si la validación falla
        if ($validator->fails()) {
            // Devolver los errores de validación
            return response()->json(['errors' => $validator->errors()], 422);
        }

        // Comprobar si ya existe un usuario con el correo electrónico proporcionado
        $existingUser = User::where('email', $request->input('email'))->first();
        if ($existingUser) {
            return response()->json(['error' => 'User already exists'], 409);
        }

        // Crear un nuevo usuario si pasa la validación y no hay usuarios existentes con el mismo correo electrónico
        $user = User::create([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'password' => bcrypt($request->input('password')),
        ]);

        // Devolver una respuesta de éxito
        return response()->json(['message' => 'User created successfully'], 201);
    }

    // Método para invalidar el token JWT y cerrar sesión
    public function logout()
    {
        try {
            JWTAuth::parseToken()->invalidate();
            return response()->json(['message' => 'Logout successful']);
        } catch (JWTException $e) {
            return response()->json(['error' => 'Failed to logout'], 500);
        }
    }
}
