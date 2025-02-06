<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class URPSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // 1. Buat Role
        $roles = [
            'developer',
            'admin',
            'accounting', // Ditambahkan karena digunakan untuk accounting
        ];

        // 2. Buat Permission
        $permissions = [
            'osano',
            'account',
            'sso',
        ];

        // Buat Permission jika belum ada
        foreach ($permissions as $permission) {
            Permission::firstOrCreate(['name' => $permission]);
        }

        // Buat Role jika belum ada
        foreach ($roles as $role) {
            Role::firstOrCreate(['name' => $role]);
        }

        // 3. Assign permission ke role developer
        $developerRole = Role::where('name', 'developer')->first();
        $developerRole->syncPermissions(['osano', 'account', 'sso']);
        $developerRole = Role::where('name', 'admin')->first();
        $developerRole->syncPermissions(['osano', 'account', 'sso']);
        $developerRole = Role::where('name', 'accounting')->first();
        $developerRole->syncPermissions(['osano', 'account']);

        // 4. Buat User Developer dan assign role + permission
        $developer = User::firstOrCreate([
            'email' => 'al@hiamalif.com',
        ], [
            'name' => 'AL Developer',
            'password' => bcrypt('password123'),
        ]);

        $developer->assignRole('developer');

        // 5. Buat User Admin
        $admin = User::firstOrCreate([
            'email' => 'admin@example.com',
        ], [
            'name' => 'Admin User',
            'password' => bcrypt('password123'),
        ]);

        $admin->assignRole('admin');

        // 6. Buat User accounting
        $accountingUsers = [
            [
                'name' => 'Accounting User 1',
                'email' => 'accounting1@example.com',
                'password' => bcrypt('password123'),
            ],
            [
                'name' => 'Accounting User 2',
                'email' => 'accounting2@example.com',
                'password' => bcrypt('password123'),
            ],
        ];

        foreach ($accountingUsers as $accountingUser) {
            $user = User::firstOrCreate(
                ['email' => $accountingUser['email']],
                $accountingUser
            );
            $user->assignRole('accounting');
        }

        $this->command->info('Users, roles, and permissions created successfully.');
    }
}
