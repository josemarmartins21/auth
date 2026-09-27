<?php

namespace Modules\Auth\Database\Seeders;

use Illuminate\Database\Seeder;
use Modules\Auth\app\Models\Role;
use Modules\Auth\Database\Factories\RoleFactory;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Role::factory()->createMany([
            [
                'name' => 'admin',
                'guard_name' => 'api',
            ], [
                'name' => 'user',
                'guard_name' => 'api'
            ]
        ]);
    }
}
