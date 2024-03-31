<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use App\Http\Controllers\RoleController;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Create admin user
            $admin = User::create([
                'name' => 'Root Admin',
                'email' => 'root@example.com',
                'password' => bcrypt('root'),
            ]);

            RoleController::AssignRole($admin->id, 'admin');


    }
}
