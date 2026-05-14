<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminUserManagementTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_list_users_with_search(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'api_token' => hash('sha256', $token = Str::random(60)),
        ]);

        User::factory()->create([
            'name' => 'Mona Searchable',
            'email' => 'mona@example.com',
        ]);

        User::factory()->create([
            'name' => 'Other Person',
            'email' => 'other@example.com',
        ]);

        $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/admin/users?search=mona')
            ->assertOk()
            ->assertJsonCount(1, 'users')
            ->assertJsonPath('users.0.email', 'mona@example.com');
    }

    public function test_admin_can_add_credits_and_toggle_user_status(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'api_token' => hash('sha256', $token = Str::random(60)),
        ]);

        $user = User::factory()->create([
            'credits' => 100,
            'is_active' => true,
        ]);

        $this->withHeader('Authorization', "Bearer {$token}")
            ->patchJson("/api/admin/users/{$user->id}", [
                'credits_delta' => 50,
                'is_active' => false,
            ])
            ->assertOk()
            ->assertJsonPath('user.credits', 150)
            ->assertJsonPath('user.is_active', false);
    }

    public function test_non_admin_cannot_access_admin_user_management_routes(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_USER,
            'api_token' => hash('sha256', $token = Str::random(60)),
        ]);

        $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/admin/users')
            ->assertForbidden();
    }
}
