<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Validator;
use App\Http\Controllers\RoleController;
use Illuminate\Support\Facades\Auth;


class AuthController extends Controller
{

    public function register(Request $request)
    {
        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:250',
            'email' => 'required|string|email|max:250|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
        ]);

        if ($validate->fails()) {
            return response()->json([
                'status' => 'failed',
                'message' => 'Validation Error!',
                'data' => $validate->errors(),
            ], 403);
        }

        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password)
        ]);

        RoleController::AssignRole($user->id, 'user');




        $data['token'] = $user->createToken($request->email)->accessToken;
        $data['user'] = $user;
        $data['role'] = RoleController::getUserRoles($user->id);

        $response = [
            'status' => 'success',
            'message' => 'User is created successfully.',
            'data' => $data,
        ];

        return response()->json($response, 201);
    }


    public function login(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|string|email',
            'password' => 'required|string'
        ]);

        // Attempt to authenticate the user
        if (!Auth::attempt($validatedData)) {
            // Return response for invalid credentials
            return response()->json([
                'status' => 'failed',
                'message' => 'Invalid credentials'
            ], 401);
        }

        $user = Auth::user();



        $userPermissions = RoleController::getUserPermissions($user->id);

        $scopes = RoleController::userHasRole($user->id,'admin') ? ['*'] : $userPermissions;
        $token = $user->createToken('User Token', $scopes)->accessToken;

        // Prepare response data
        $response = [
            'status' => 'success',
            'message' => 'User is logged in successfully.',
            'name' => $user->name,
            'email' => $user->email,
            'role' => RoleController::getUserRoles($user->id),
            'token' => $token
        ];

        // Return the response
        return response()->json($response, 200);
    }


    public function logout(Request $request)
    {
        auth()->user()->tokens()->delete();
        return response()->json([
            'status' => 'success',
            'message' => 'User is logged out successfully'
        ], 200);
    }



}
