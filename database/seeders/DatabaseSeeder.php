<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        $superAdmin = Role::firstOrCreate(['name' => 'super-admin']);

        $user = User::firstOrCreate(
            ['email' => 'admin@resto.com'],
            [
                'name'     => 'Admin',
                'password' => bcrypt('password'),
            ]
        );

        $user->assignRole($superAdmin);

        $this->call(PermissionSeeder::class);
        $this->call(CaisseSeeder::class);
    }
}
