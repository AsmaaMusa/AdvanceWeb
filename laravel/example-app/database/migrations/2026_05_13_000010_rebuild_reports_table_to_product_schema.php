<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('reports', 'report_code')) {
            return;
        }

        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('reports', function ($table): void {
                $table->unsignedInteger('credits_used')->default(0)->after('project_id');
                $table->json('sections')->nullable()->after('credits_used');
                $table->json('selected_sections')->nullable()->after('sections');
                $table->dropColumn(['report_code', 'title', 'type', 'language', 'status', 'pages', 'generated_at']);
            });

            return;
        }

        DB::statement('PRAGMA foreign_keys = OFF');

        DB::statement(<<<'SQL'
            CREATE TABLE reports_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                user_id INTEGER NOT NULL,
                project_id INTEGER NOT NULL,
                credits_used INTEGER NOT NULL,
                sections TEXT NOT NULL,
                selected_sections TEXT NOT NULL,
                created_at datetime NULL,
                updated_at datetime NULL,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE,
                FOREIGN KEY (project_id) REFERENCES projects(id) ON DELETE CASCADE
            )
        SQL);

        $reports = DB::table('reports')->get();

        foreach ($reports as $report) {
            DB::table('reports_new')->insert([
                'id' => $report->id,
                'user_id' => $report->user_id,
                'project_id' => $report->project_id,
                'credits_used' => (int) ($report->pages ?? 0),
                'sections' => json_encode(array_values(array_filter([
                    $report->type ?? null,
                    $report->title ?? null,
                    $report->language ?? null,
                    $report->status ?? null,
                ])), JSON_THROW_ON_ERROR),
                'selected_sections' => json_encode(array_values(array_filter([
                    $report->type ?? null,
                    $report->title ?? null,
                ])), JSON_THROW_ON_ERROR),
                'created_at' => $report->generated_at ?? $report->created_at,
                'updated_at' => $report->updated_at,
            ]);
        }

        DB::statement('DROP TABLE reports');
        DB::statement('ALTER TABLE reports_new RENAME TO reports');
        DB::statement('PRAGMA foreign_keys = ON');
    }

    public function down(): void
    {
        //
    }
};
