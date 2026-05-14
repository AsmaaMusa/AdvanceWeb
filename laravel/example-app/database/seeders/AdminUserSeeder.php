<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;

class AdminUserSeeder extends Seeder
{
    public function run(): void
    {
        User::query()->updateOrCreate(
            ['email' => env('ADMIN_EMAIL', 'admin@strategai.com')],
            [
                'name' => env('ADMIN_NAME', 'StrategAI Super Admin'),
                'role' => User::ROLE_ADMIN,
                'credits' => 25000,
                'projects_count' => 18,
                'is_active' => true,
                'password' => env('ADMIN_PASSWORD', 'admin12345'),
                'email_verified_at' => now(),
                'api_token' => null,
            ],
        );
    }
}
