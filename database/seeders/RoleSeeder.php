<?php

declare(strict_types=1);

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    public function run(): void
    {
        $roles = [
            [
                'name' => \App\Enum\Role::ADMIN->value,
                'guard_name' => 'web',
            ],
            [
                'name' => \App\Enum\Role::SUPER_ADMIN->value,
                'guard_name' => 'web',
            ],
            [
                'name' => \App\Enum\Role::MODERATOR->value,
                'guard_name' => 'web',
            ],
            [
                'name' =>  \App\Enum\Role::CASHIER->value,
                'guard_name' => 'web',
            ]
        ];

        Role::insert($roles);
    }
}
