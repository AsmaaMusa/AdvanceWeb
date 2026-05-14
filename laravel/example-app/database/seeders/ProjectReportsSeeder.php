<?php

namespace Database\Seeders;

use App\Models\Project;
use App\Models\Report;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Carbon;

class ProjectReportsSeeder extends Seeder
{
    public function run(): void
    {
        $definitions = [
            [
                'project' => [
                    'user_email' => 'olivia.m@email.com',
                    'name' => 'Najd Village Growth Plan',
                    'business_type' => 'Restaurant',
                    'language' => 'Arabic',
                ],
                'reports' => [
                    ['code' => 'RPT-4821', 'title' => 'Market Analysis', 'type' => 'Market Analysis', 'language' => 'Arabic', 'status' => 'Pending', 'pages' => 14, 'days_ago' => 1],
                    ['code' => 'RPT-4810', 'title' => 'Operations Review', 'type' => 'Operations', 'language' => 'English', 'status' => 'Reviewed', 'pages' => 12, 'days_ago' => 3],
                    ['code' => 'RPT-4760', 'title' => 'Pricing Strategy', 'type' => 'Pricing', 'language' => 'Arabic', 'status' => 'Resolved', 'pages' => 11, 'days_ago' => 9],
                ],
            ],
            [
                'project' => [
                    'user_email' => 'j.thornton@email.com',
                    'name' => 'Al-Safa Expansion',
                    'business_type' => 'Bakery',
                    'language' => 'Arabic',
                ],
                'reports' => [
                    ['code' => 'RPT-4817', 'title' => 'Market Analysis', 'type' => 'Market Analysis', 'language' => 'Arabic', 'status' => 'Pending', 'pages' => 17, 'days_ago' => 2],
                    ['code' => 'RPT-4802', 'title' => 'Demand Forecast', 'type' => 'Forecast', 'language' => 'Arabic', 'status' => 'Reviewed', 'pages' => 13, 'days_ago' => 5],
                ],
            ],
            [
                'project' => [
                    'user_email' => 'sophie.n@email.com',
                    'name' => 'Urban Tech Positioning',
                    'business_type' => 'Technology',
                    'language' => 'English',
                ],
                'reports' => [
                    ['code' => 'RPT-4804', 'title' => 'SWOT Analysis', 'type' => 'SWOT', 'language' => 'English', 'status' => 'Reviewed', 'pages' => 11, 'days_ago' => 4],
                    ['code' => 'RPT-4780', 'title' => 'Business Strategy', 'type' => 'Business Strategy', 'language' => 'English', 'status' => 'Resolved', 'pages' => 15, 'days_ago' => 8],
                    ['code' => 'RPT-4740', 'title' => 'Investor Brief', 'type' => 'Investor Brief', 'language' => 'English', 'status' => 'Resolved', 'pages' => 16, 'days_ago' => 15],
                ],
            ],
            [
                'project' => [
                    'user_email' => 'a.clarke@email.com',
                    'name' => 'Green Cart Scale-Up',
                    'business_type' => 'Retail',
                    'language' => 'Arabic',
                ],
                'reports' => [
                    ['code' => 'RPT-4798', 'title' => 'Growth Plan', 'type' => 'Growth Plan', 'language' => 'Arabic', 'status' => 'Resolved', 'pages' => 19, 'days_ago' => 6],
                    ['code' => 'RPT-4775', 'title' => 'Retail Benchmark', 'type' => 'Benchmark', 'language' => 'Arabic', 'status' => 'Reviewed', 'pages' => 10, 'days_ago' => 10],
                ],
            ],
            [
                'project' => [
                    'user_email' => 'liam.h@email.com',
                    'name' => 'Future SaaS Launch',
                    'business_type' => 'SaaS',
                    'language' => 'English',
                ],
                'reports' => [
                    ['code' => 'RPT-4788', 'title' => 'Campaign Strategy', 'type' => 'Marketing', 'language' => 'English', 'status' => 'Rejected', 'pages' => 15, 'days_ago' => 7],
                    ['code' => 'RPT-4755', 'title' => 'Customer Personas', 'type' => 'Research', 'language' => 'English', 'status' => 'Pending', 'pages' => 9, 'days_ago' => 11],
                    ['code' => 'RPT-4710', 'title' => 'Launch Readiness', 'type' => 'Operations', 'language' => 'Arabic', 'status' => 'Resolved', 'pages' => 12, 'days_ago' => 18],
                ],
            ],
        ];

        foreach ($definitions as $definition) {
            $user = User::query()->where('email', $definition['project']['user_email'])->first();

            if (! $user) {
                continue;
            }

            $project = Project::query()->updateOrCreate(
                ['name' => $definition['project']['name']],
                [
                    'user_id' => $user->id,
                    'business_type' => $definition['project']['business_type'],
                    'language' => $definition['project']['language'],
                ],
            );

            foreach ($definition['reports'] as $reportDefinition) {
                $createdAt = Carbon::now()->subDays($reportDefinition['days_ago'])->setTime(10, 0);

                Report::query()->updateOrCreate(
                    [
                        'project_id' => $project->id,
                        'user_id' => $user->id,
                        'created_at' => $createdAt,
                    ],
                    [
                        'credits_used' => $reportDefinition['pages'],
                        'sections' => [
                            'code' => $reportDefinition['code'],
                            'title' => $reportDefinition['title'],
                            'type' => $reportDefinition['type'],
                            'language' => $reportDefinition['language'],
                            'status' => $reportDefinition['status'],
                        ],
                        'selected_sections' => [
                            $reportDefinition['type'],
                            $reportDefinition['title'],
                        ],
                        'updated_at' => $createdAt,
                    ],
                );

                Transaction::query()->updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'description' => "Credits used for {$reportDefinition['title']}",
                        'created_at' => $createdAt,
                    ],
                    [
                        'type' => Transaction::TYPE_DEDUCT,
                        'amount' => $reportDefinition['pages'],
                        'status' => Transaction::STATUS_SUCCESS,
                        'updated_at' => $createdAt,
                    ],
                );
            }
        }
    }
}
