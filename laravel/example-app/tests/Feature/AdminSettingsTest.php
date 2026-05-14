<?php

namespace Tests\Feature;

use App\Models\AdminSession;
use App\Models\AdminSetting;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminSettingsTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_update_security_settings(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'api_token' => hash('sha256', $token = Str::random(60)),
        ]);

        $this->withHeader('Authorization', "Bearer {$token}")
            ->putJson('/api/admin/settings', [
                'security' => [
                    'require_strong_passwords' => false,
                ],
            ])
            ->assertOk()
            ->assertJsonPath('settings.security.require_strong_passwords', false);

        $this->assertFalse(
            AdminSetting::query()
                ->where('key', 'admin_settings')
                ->first()
                ->value['security']['require_strong_passwords'],
        );
    }

    public function test_admin_can_regenerate_token_and_old_token_stops_working(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);
        $token = Str::random(60);

        AdminSession::create([
            'user_id' => $admin->id,
            'token_hash' => hash('sha256', $token),
            'last_used_at' => now(),
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->putJson('/api/admin/settings', [
                'security_action' => 'regenerate_token',
            ])
            ->assertOk()
            ->assertJsonStructure(['token']);

        $newToken = $response->json('token');

        $this->assertNotSame($token, $newToken);
        $this->assertTrue(
            AdminSession::query()
                ->where('user_id', $admin->id)
                ->where('token_hash', hash('sha256', $newToken))
                ->exists(),
        );

        $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/admin/settings')
            ->assertUnauthorized();

        $this->withHeader('Authorization', "Bearer {$newToken}")
            ->getJson('/api/admin/settings')
            ->assertOk();
    }

    public function test_admin_can_logout_all_devices_by_clearing_token(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
        ]);
        $token = Str::random(60);
        $otherToken = Str::random(60);

        AdminSession::create([
            'user_id' => $admin->id,
            'token_hash' => hash('sha256', $token),
            'last_used_at' => now(),
        ]);

        AdminSession::create([
            'user_id' => $admin->id,
            'token_hash' => hash('sha256', $otherToken),
            'last_used_at' => now(),
        ]);

        $this->withHeader('Authorization', "Bearer {$token}")
            ->putJson('/api/admin/settings', [
                'security_action' => 'logout_all',
            ])
            ->assertOk();

        $this->assertNull($admin->fresh()->api_token);
        $this->assertSame(0, AdminSession::query()->where('user_id', $admin->id)->count());

        $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/admin/settings')
            ->assertUnauthorized();

        $this->withHeader('Authorization', "Bearer {$otherToken}")
            ->getJson('/api/admin/settings')
            ->assertUnauthorized();
    }

    public function test_settings_show_returns_last_login_time(): void
    {
        $lastLogin = now()->subMinutes(15)->startOfSecond();

        User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'api_token' => hash('sha256', $token = Str::random(60)),
            'last_admin_login_at' => $lastLogin,
        ]);

        $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/admin/settings')
            ->assertOk()
            ->assertJsonPath('profile.last_login_at', $lastLogin->toISOString());
    }
}
