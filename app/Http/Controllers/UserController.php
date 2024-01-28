<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Validator;

class UserController extends Controller
{
    public function index()
    {
        $users = User::all();
        return response()->json([
            'success' => true,
            'data' => $users,
        ]);
    }

    public function show($id)
    {
        $user = User::find($id);
        if ($user) {
            return response()->json([
                'success' => true,
                'data' => $user,
            ]);
        } else {
            return $this->userNotFoundResponse();
        }
    }
    public function store(Request $request)
    {
        // Validate the incoming request data
        $validator = Validator::make($request->all(), [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        // Check if the validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // If validation passes, proceed to create the user
        $userData = $request->only('name', 'email');
        $userData['password'] = bcrypt($request->input('password'));
        $user = User::create($userData);

        // Return success response with the created user data
        return response()->json([
            'success' => true,
            'data' => $user,
        ], 201);
    }


    public function update(Request $request, $id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->userNotFoundResponse();
        }

        // Define validation rules
        $rules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
        ];

        // Create a validator instance
        $validator = Validator::make($request->all(), $rules);

        // Check if validation fails
        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'errors' => $validator->errors(),
            ], 422);
        }

        // If validation passes, update the user data
        $userData = $request->only('name', 'email');
        if ($request->filled('password')) {
            $userData['password'] = bcrypt($request->input('password'));
        }
        $user->update($userData);

        // Return success response with updated user data
        return response()->json([
            'success' => true,
            'data' => $user,
        ]);
    }

    public function destroy($id)
    {
        $user = User::find($id);
        if (!$user) {
            return $this->userNotFoundResponse();
        }

        $user->delete();

        return response()->json([
            'success' => true,
            'message' => 'User deleted successfully.',
        ]);
    }

    private function userNotFoundResponse()
    {
        return response()->json([
            'success' => false,
            'message' => 'User not found.',
        ], 404);
    }
}
