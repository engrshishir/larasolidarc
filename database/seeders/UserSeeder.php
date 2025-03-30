<?php

declare(strict_types=1);

namespace Database\Seeders;

use App\Services\UserService;
use Illuminate\Database\Seeder;

class UserSeeder extends Seeder
{
    public function __construct(private readonly UserService $userService) {
    }

    /**
     * Run the database seeds.
    */
    public function run(): void
    {
        $this->userService->create([
            'name' => 'Admin',
            'email' => 'admin@example.com',
            'phone' => '01314925185',
            'password' => 'password',
            'role' => 'Super Admin'
        ]);

        $this->userService->create([
            'name' => 'grahok',
            'email' => 'grahok@gmail.com',
            'phone' => '01314925181',
            'password' => 'password',
            'role' => 'Moderator'
        ]);
    }
}
