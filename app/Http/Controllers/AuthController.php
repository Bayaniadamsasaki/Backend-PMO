<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Auth;

class AuthController extends Controller
{
    // Insert User/Register
    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'name' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password' => 'required|string|min:8',
            'role' => 'sometimes|string|in:user,admin', // Validasi untuk role
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'role' => $request->role ?? 'user', // Default role 'user' jika tidak disertakan
        ]);

        $token = $user->createToken('AuthToken')->plainTextToken;

        return response()->json([
            'message' => 'User successfully registered',
            'user' => $user,
            'token' => $token
        ], 201);
    }

    // Get Users
    public function getUsers()
    {
        $users = User::all();
        return response()->json(['users' => $users], 200);
    }

    // Login user
    public function login(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'email' => 'required|string|email',
            'password' => 'required|string',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::where('email', $request->email)->first();

        if (!$user || !Hash::check($request->password, $user->password)) {
            return response()->json([
                'message' => 'Invalid credentials'
            ], 401);
        }

        $accessToken = $user->createToken('AuthToken')->plainTextToken;
        $refreshToken = $user->createToken('RefreshToken')->plainTextToken;
        //role
        $role = $user->role;

        return response()->json([
            'access_token' => $accessToken,
            'refresh_token' => $refreshToken,
            'role' => $role
        ], 200);
    }

    // Logout user
    public function logout(Request $request)
    {
        if (!$request->user()) {
            return response()->json([
                'message' => 'User not authenticated'
            ], 401);
        }

        $request->user()->tokens()->delete(); // Menghapus semua token akses yang terkait dengan pengguna ini

        return response()->json([
            'message' => 'User successfully logged out'
        ], 200);
    }


    // Delete user
    public function deleteUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:users,id',
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::find($request->id);

        if (!$user) {
            return response()->json([
                'message' => 'User not found'
            ], 404);
        }

        $user->delete();

        return response()->json([
            'message' => 'User successfully deleted'
        ], 200);
    }

    // Update User
    public function updateUser(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'id' => 'required|integer|exists:users,id',
            'name' => 'sometimes|required|string|max:255',
            'email' => 'sometimes|required|string|email|max:255|unique:users,email,' . $request->id,
            'password' => 'sometimes|required|string|min:8',
            'role' => 'sometimes|string|in:user,admin', // Validasi untuk role
        ]);

        if ($validator->fails()) {
            return response()->json($validator->errors(), 400);
        }

        $user = User::find($request->id);

        if (!$user) {
            return response()->json([
                'message' => 'Pengguna tidak ditemukan'
            ], 404);
        }

        if ($request->has('name')) {
            $user->name = $request->name;
        }

        if ($request->has('email')) {
            $user->email = $request->email;
        }

        if ($request->has('password')) {
            $user->password = Hash::make($request->password);
        }

        if ($request->has('role')) {
            $user->role = $request->role;
        }

        $user->save();

        return response()->json([
            'message' => 'Pengguna berhasil diperbarui',
            'user' => $user
        ], 200);
    }
}
