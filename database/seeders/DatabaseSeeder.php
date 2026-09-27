<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Modules\Auth\app\Models\Permission;
use Modules\Auth\app\Models\Role;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // User::factory(10)->create();

        $user = User::factory()->create([
            'name' => 'josimar',
           'email' => 'josimar@email.ao',
           'password' => Hash::make('123456'),
        ]);

        $roles = [
            [
                'name' => 'admin',
                'guard_name' => 'api',
            ], [
                'name' => 'user',
                'guard_name' => 'api'
            ]
        ];

        foreach ($roles as $data) {
            Role::create($data);
            
        }

        $permissions = [
            [
                'name' => 'ver usuário',
                'guard_name' => 'api',
            ],
            [
                'name' => 'criar usuário',
                'guard_name' => 'api',
            ],
            [
                'name' => 'editar usuário',
                'guard_name' => 'api',
            ],
            [
                'name' => 'excluir usuário',
                'guard_name' => 'api',
            ],
        ];

        foreach ($permissions as $data) {
            Permission::create($data);
        }

        $admin = Role::where('name', 'admin')->first();

        
        $permissions = Permission::whereIn('name', [
            'ver usuário',
            'ver usuários',
            'criar usuário',
            'editar usuário',
            'excluir usuário',
        ])->get();

        $admin->givePermissionTo($permissions);

        $user->assignRole($admin);

    }
}
