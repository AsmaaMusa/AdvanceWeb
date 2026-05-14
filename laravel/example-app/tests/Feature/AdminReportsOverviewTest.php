<?php

namespace Tests\Feature;

use App\Models\Project;
use App\Models\Report;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Carbon;
use Illuminate\Support\Str;
use Tests\TestCase;

class AdminReportsOverviewTest extends TestCase
{
    use RefreshDatabase;

    public function test_admin_can_view_reports_overview_with_projects_and_counts(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'api_token' => hash('sha256', $token = Str::random(60)),
        ]);

        $user = User::factory()->create();
        $project = Project::query()->create([
            'user_id' => $user->id,
            'name' => 'Demo Project',
            'business_type' => 'Retail',
        ]);

        Report::query()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'credits_used' => 12,
            'sections' => [
                'code' => 'RPT-9000',
                'title' => 'Growth Plan',
                'type' => 'Growth Plan',
                'language' => 'Arabic',
                'status' => 'Pending',
            ],
            'selected_sections' => ['Growth Plan'],
            'created_at' => Carbon::now()->subDay(),
        ]);

        $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/admin/reports-overview')
            ->assertOk()
            ->assertJsonPath('stats.total_reports', 1)
            ->assertJsonPath('stats.projects_total', 1)
            ->assertJsonPath('projects.0.reports_count', 1)
            ->assertJsonPath('reports.0.report_code', 'RPT-9000');
    }

    public function test_admin_can_filter_reports_by_type_language_and_date(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'api_token' => hash('sha256', $token = Str::random(60)),
        ]);

        $user = User::factory()->create();
        $project = Project::query()->create([
            'user_id' => $user->id,
            'name' => 'Filter Project',
            'business_type' => 'SaaS',
        ]);

        Report::query()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'credits_used' => 10,
            'sections' => [
                'code' => 'RPT-1000',
                'title' => 'Arabic Growth',
                'type' => 'Growth Plan',
                'language' => 'Arabic',
                'status' => 'Pending',
            ],
            'selected_sections' => ['Growth Plan'],
            'created_at' => Carbon::parse('2026-04-20 10:00:00'),
        ]);

        Report::query()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'credits_used' => 15,
            'sections' => [
                'code' => 'RPT-1001',
                'title' => 'English SWOT',
                'type' => 'SWOT',
                'language' => 'English',
                'status' => 'Reviewed',
            ],
            'selected_sections' => ['SWOT'],
            'created_at' => Carbon::parse('2026-04-10 10:00:00'),
        ]);

        $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/admin/reports-overview?type=Growth%20Plan&language=Arabic&from=2026-04-15&to=2026-04-21')
            ->assertOk()
            ->assertJsonCount(1, 'reports')
            ->assertJsonPath('reports.0.report_code', 'RPT-1000');
    }

    public function test_non_admin_cannot_access_reports_overview(): void
    {
        $user = User::factory()->create([
            'role' => User::ROLE_USER,
            'api_token' => hash('sha256', $token = Str::random(60)),
        ]);

        $this->withHeader('Authorization', "Bearer {$token}")
            ->getJson('/api/admin/reports-overview')
            ->assertForbidden();
    }

    public function test_admin_can_export_filtered_reports_as_csv(): void
    {
        $admin = User::factory()->create([
            'role' => User::ROLE_ADMIN,
            'api_token' => hash('sha256', $token = Str::random(60)),
        ]);

        $user = User::factory()->create();
        $project = Project::query()->create([
            'user_id' => $user->id,
            'name' => 'Export Project',
            'business_type' => 'Retail',
        ]);

        Report::query()->create([
            'project_id' => $project->id,
            'user_id' => $user->id,
            'credits_used' => 13,
            'sections' => [
                'code' => 'RPT-CSV-1',
                'title' => 'CSV Growth Plan',
                'type' => 'Growth Plan',
                'language' => 'Arabic',
                'status' => 'Pending',
            ],
            'selected_sections' => ['Growth Plan'],
            'created_at' => Carbon::parse('2026-04-20 10:00:00'),
        ]);

        $response = $this->withHeader('Authorization', "Bearer {$token}")
            ->get('/api/admin/reports-overview/export?type=Growth%20Plan');

        $response->assertOk();
        $response->assertHeader('content-type', 'text/csv; charset=UTF-8');
        $this->assertStringContainsString('RPT-CSV-1', $response->streamedContent());
        $this->assertStringContainsString('CSV Growth Plan', $response->streamedContent());
    }
}
