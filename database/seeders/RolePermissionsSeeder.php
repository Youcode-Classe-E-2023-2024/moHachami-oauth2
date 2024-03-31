<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Permission;
use App\Models\Role;
use App\Http\Controllers\RoleController;

class RolePermissionsSeeder extends Seeder
{

    private $roles = ['admin','user'];

    private $permissions = [
        'add-user',
        'update-user',
        'read-user',
        'delete-user',
        'role-management'
    ];

    /**
     * Seed the application's database.
     */

    public function run(): void
    {
        foreach ($this->permissions as $permission) {
            Permission::create(['name' => $permission]);
        }

        foreach ($this->roles as $role) {
            Role::create(['name' => $role]);
        }

        RoleController::givePermissionsToRole('admin', $this->permissions);
        RoleController::givePermissionsToRole('user', ['read-user']);

    }
}

