<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Hash;

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
        $request->validate([
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email',
            'password' => 'required|string|min:6',
        ]);

        $userData = $request->all();
        $userData['password'] = bcrypt($request->input('password'));
        $user = User::create($userData);

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

        $rules = [
            'name' => 'required|string',
            'email' => 'required|email|unique:users,email,' . $id,
            'password' => 'sometimes|string|min:6',
        ];

        if ($request->has('password')) {
            $rules['password'] = 'required|string|min:6';
        }

        $request->validate($rules);

        $userData = $request->all();
        if ($request->has('password')) {
            $userData['password'] = bcrypt($request->input('password'));
        }

        $user->update($userData);

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
