<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class UserManagementSeeder extends Seeder
{
    public function run(): void
    {
        $users = [
            [
                'name' => 'Olivia Martinez',
                'email' => 'olivia.m@email.com',
                'credits' => 4820,
                'projects_count' => 12,
                'is_active' => true,
                'password' => 'password123',
            ],
            [
                'name' => 'James Thornton',
                'email' => 'j.thornton@email.com',
                'credits' => 11200,
                'projects_count' => 34,
                'is_active' => true,
                'password' => 'password123',
            ],
            [
                'name' => 'Sophie Nguyen',
                'email' => 'sophie.n@email.com',
                'credits' => 2150,
                'projects_count' => 7,
                'is_active' => true,
                'password' => 'password123',
            ],
            [
                'name' => 'Marcus Reid',
                'email' => 'm.reid@email.com',
                'credits' => 340,
                'projects_count' => 2,
                'is_active' => false,
                'password' => 'password123',
            ],
            [
                'name' => 'Amelia Clarke',
                'email' => 'a.clarke@email.com',
                'credits' => 19800,
                'projects_count' => 51,
                'is_active' => true,
                'password' => 'password123',
            ],
            [
                'name' => 'Liam Hoffman',
                'email' => 'liam.h@email.com',
                'credits' => 750,
                'projects_count' => 3,
                'is_active' => false,
                'password' => 'password123',
            ],
        ];

        foreach ($users as $user) {
            User::query()->updateOrCreate(
                ['email' => $user['email']],
                [
                    ...$user,
                    'role' => User::ROLE_USER,
                    'email_verified_at' => now(),
                    'api_token' => null,
                ],
            );
        }
    }
}
