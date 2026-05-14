<?php

namespace Tests\Feature;

use App\Models\AdminSession;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class AdminAuthTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_log_in_and_receive_a_token(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'email' => 'admin@strategai.com',
            'password' => 'admin12345',
        ]);

        $response = $this->postJson('/api/admin/login', [
            'email' => $admin->email,
            'password' => 'admin12345',
        ]);

        $response
            ->assertOk()
            ->assertJsonPath('user.role', User::ROLE_ADMIN)
            ->assertJsonStructure([
                'message',
                'token',
                'user' => ['id', 'name', 'email', 'role'],
            ]);

        $this->assertTrue(
            AdminSession::query()
                ->where('user_id', $admin->id)
                ->where('token_hash', hash('sha256', $response->json('token')))
                ->exists(),
        );
    }

    public function test_regular_user_is_blocked_from_admin_login(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_USER,
            'password' => 'user12345',
        ]);

        $this->postJson('/api/admin/login', [
            'email' => $user->email,
            'password' => 'user12345',
        ])->assertForbidden();
    }

    public function test_admin_middleware_blocks_requests_without_a_valid_token(): void
    {
        $this->getJson('/api/admin/me')->assertUnauthorized();
    }
}
