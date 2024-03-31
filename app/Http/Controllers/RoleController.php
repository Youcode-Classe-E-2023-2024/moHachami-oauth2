<?php

namespace App\Http\Controllers;

use App\Models\Permission;
use App\Models\Role;
use App\Models\RolePermission;
use App\Models\UserRole;
use Illuminate\Http\Request;

class RoleController extends Controller
{
    static function AssignRole($user, $role)
    {
        $roleId = Role::where('name', $role)->pluck('id');
        UserRole::create([
            'user_id' => $user,
            'role_id' => $roleId[0]
        ]);
    }

    static function givePermissionsToRole($role, $permissions)
    {
        $roleModel = Role::where('name', $role)->first();
        if (!$roleModel) {
            return; // Role not found
        }

        foreach ($permissions as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();
            if ($permission) {
                RolePermission::create([
                    'role_id' => $roleModel->id,
                    'permission_id' => $permission->id
                ]);
            }
        }
    }

    static function userCan($user_id, $permissions)
    {
        $userRole = UserRole::where('user_id', $user_id)->first();

        if (!$userRole) {
            return response()->json([
                'error' => 'User role not found'
            ], 404);
        }

        $rolePermissions = RolePermission::where('role_id', $userRole->role_id)->pluck('permission_id');

        if ($rolePermissions->isEmpty()) {
            return response()->json([
                'error' => 'Role permissions not found'
            ], 404);
        }

        $permissionsIds = Permission::whereIn('name', $permissions)->pluck('id')->toArray();

        foreach ($permissionsIds as $permission) {
            if (!$rolePermissions->contains($permission)) {
                return false;
            }
        }

        return true;
    }


    static function userHasRole($user_id, $role)
    {
        $userRole = UserRole::where('user_id', $user_id)->get()->first();

        if (!$userRole) {
            return response()->json([
                'error' => 'User role not found'
            ], 404);
        }

        $role_id = Role::where('name', $role)->value('id');

        if (!$role_id) {
            return response()->json([
                'error' => 'Role not found'
            ], 404);
        }

        if ($userRole->role_id !== $role_id || !$userRole) {
            return false;
        }

        return true;
    }

    function AddPermission(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3'
        ]);

        Permission::create([
            'name' => $request->name
        ]);
        return response()->json([
            'status' => 'permission added successfully'
        ]);
    }

    function AddRole(Request $request)
    {
        $request->validate([
            'name' => 'required|min:3'
        ]);

        Role::create([
            'name' => $request->name
        ]);
        return response()->json([
            'status' => 'role added successfully'
        ]);
    }

    static function getUserRoles($userId)
    {
        try {
            $userRole = UserRole::where('user_id', $userId)->first();

            if (!$userRole) {
                return response()->json(['error' => 'User role not found'], 404);
            }

            $role = Role::find($userRole->role_id);

            if (!$role) {
                return response()->json(['error' => 'Role not found'], 404);
            }

            return $role->name; // Return the name of the role
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    static function getUserPermissions($userId)
    {
        try {
            $userRole = UserRole::where('user_id', $userId)->first();

            if (!$userRole) {
                return response()->json(['error' => 'User role not found'], 404);
            }

            $rolePermissions = RolePermission::where('role_id', $userRole->role_id)
                ->pluck('permission_id'); // Pluck permission IDs from the collection

            if ($rolePermissions->isEmpty()) {
                return response()->json(['error' => 'Role permissions not found'], 404);
            }

            $permissions = Permission::whereIn('id', $rolePermissions)->pluck('name')->toArray();

            return $permissions;
        } catch (\Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    public function AssignRoleToPermission(Request $request){
        $request->validate([
            'roleName' => 'required|min:3',
            'permissions' => 'required|array',
            'permissions.*' => 'string|min:3',
        ]);

        $role = Role::where('name', $request->roleName)->first();

        if (!$role) {
            return response()->json(['error' => 'Role not found.'], 404);
        }

        $permissionNames = $request->permissions;

        foreach ($permissionNames as $permissionName) {
            $permission = Permission::where('name', $permissionName)->first();

            if ($permission) {
                $existingRolePermission = RolePermission::where('role_id', $role->id)
                    ->where('permission_id', $permission->id)
                    ->exists();

                if (!$existingRolePermission) {
                    RolePermission::create([
                        'role_id' => $role->id,
                        'permission_id' => $permission->id
                    ]);
                }
            }
        }

        return response()->json(['message' => 'Permissions assigned to role successfully.'], 200);
    }

}
