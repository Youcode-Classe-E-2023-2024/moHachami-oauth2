<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\AccessTokenController;
use App\Http\Controllers\Auth\AuthController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\RoleController;



/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider and all of them will
| be assigned to the "api" middleware group. Make something great!
|
*/


Route::middleware('auth:api')->get('/user', function (Request $request) {
    return $request->user();
});

Route::post('auth/login', [AuthController::class, 'login']);
Route::post('auth/register', [AuthController::class, 'register']);

// Route::middleware(['auth:api', 'scope:read-posts'])->get('/posts', [PostController::class, 'index']);
// Route::middleware(['auth:api', 'scope:write-posts'])->post('/posts', [PostController::class, 'store']);

Route::middleware(['auth:api'])->group(function () {


    Route::get('/profile', [UserController::class, 'index']);
    Route::get('profile/{id}', [UserController::class, 'getUserDetail']);




    Route::get('users', [UserController::class, 'index']);
    Route::post('/users', [UserController::class, 'store']);
    Route::put('/users/{id}', [UserController::class, 'update']);
    Route::delete('/users/{id}', [UserController::class, 'destroy']);
    Route::post('assignRole', [UserController::class, 'assignRole']);

    Route::post('AddRole', [RoleController::class, 'AddRole']);
    Route::post('AddPermission', [RoleController::class, 'AddPermission']);
    Route::post('AssignRoleToPermission', [RoleController::class, 'AssignRoleToPermission']);


    Route::post('auth/logout', [AuthController::class, 'logout']);


});

