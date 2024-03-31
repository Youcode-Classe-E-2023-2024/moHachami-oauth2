<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\User;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Validator;
use Illuminate\Support\Facades\Gate;


class UserController extends Controller
{
    public function index(Request $request)
    {
        if (!$request->user()->tokenCan('read-user')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $users = User::all();
        return response()->json($users);
    }



    function store(Request $request)
    {

        if (!$request->user()->tokenCan('add-user')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validate = Validator::make($request->all(), [
            'name' => 'required|string|max:250',
            'email' => 'required|string|email:rfc,dns|max:250|unique:users,email',
            'password' => 'required|string|min:8|confirmed',
            'userGroup' => 'required',
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

        $user->assignRole('userGrp1');


        $data['token'] = $user->createToken(
            'user-group',
            [
                $request->userGroup
            ]
        )->accessToken;
        $data['user'] = $user;

        $response = [
            'status' => 'success',
            'message' => 'User is created successfully.',
            'data' => $data,
        ];

        return response()->json($response, 201);
    }

    function update(Request $request)
    {
        if (!$request->user()->tokenCan('update-user')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $validator = Validator::make($request->all(), [
            'name' => 'string',
            'password' => 'string|min:8'
        ]);

        if ($validator->fails()) {
            return response()->json(['errors' => $validator->errors()], 422);
        }
        $user = User::find($request->id);

        if (isset($request->name)) {
            $user->name = $request->name;
        }
        if (isset($request->password)) {
            $user->password = $request->password;
        }

        $user->save();
        return response()->json([
            'status' => 'success'
        ]);
    }

    function destroy(Request $request)
    {
        if (!$request->user()->tokenCan('delete-user')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $user = User::find($request->id);
        $user->delete();
        return response()->json([
            'status' => 'success'
        ]);
    }

    function AssignRole(Request $request)
    {
        if (!$request->user()->tokenCan('role-management')) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $user_id = $request->user_id;
        $role = $request->role;

        RoleController::AssignRole($user_id, $role);

        return response()->json([
            'status' => 'role assigned successfully'
        ]);
    }

    function getUserDetail($id){
        $authenticatedUser = Auth::user();

        $isAdmin = RoleController::userHasRole($authenticatedUser->id, 'admin');

        $isRequestedUser = $id == $authenticatedUser->id;




        if ($isAdmin || $isRequestedUser) {
            $user = User::find($id);

            if ($user) {
                return response()->json(['user' => $user], 200);
            } else {
                return response()->json(['error' => 'User not found'], 404);
            }



            $user = User::find($id)->get()->first();
            return response()->json(['user' => $user], 200);
        }else{
            return response()->json(['error' => 'Unauthorized'], 404);

        }

    }
}
