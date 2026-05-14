<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('projects', 'business_name') && ! Schema::hasColumn('projects', 'industry')) {
            return;
        }

        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('projects', function (Blueprint $table): void {
                if (Schema::hasColumn('projects', 'business_name')) {
                    $table->dropColumn('business_name');
                }

                if (Schema::hasColumn('projects', 'industry')) {
                    $table->dropColumn('industry');
                }
            });

            return;
        }

        $this->rebuildProjectsTable();
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            if (! Schema::hasColumn('projects', 'business_name')) {
                $table->string('business_name')->nullable()->after('name');
            }

            if (! Schema::hasColumn('projects', 'industry')) {
                $table->string('industry')->nullable()->after('business_name');
            }
        });
    }

    private function rebuildProjectsTable(): void
    {
        DB::statement('PRAGMA foreign_keys = OFF');

        DB::statement(<<<'SQL'
            CREATE TABLE projects_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                user_id INTEGER NOT NULL,
                name varchar NOT NULL,
                business_type varchar NOT NULL DEFAULT 'General',
                description TEXT NULL,
                stage varchar NULL,
                employees varchar NULL,
                budget varchar NULL,
                market TEXT NULL,
                competitors TEXT NULL,
                language varchar NOT NULL DEFAULT 'English',
                created_at datetime NULL,
                updated_at datetime NULL,
                FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE CASCADE
            )
        SQL);

        DB::statement(<<<'SQL'
            INSERT INTO projects_new (
                id,
                user_id,
                name,
                business_type,
                description,
                stage,
                employees,
                budget,
                market,
                competitors,
                language,
                created_at,
                updated_at
            )
            SELECT
                id,
                user_id,
                name,
                COALESCE(NULLIF(business_type, ''), NULLIF(industry, ''), 'General'),
                description,
                stage,
                employees,
                budget,
                market,
                competitors,
                COALESCE(NULLIF(language, ''), 'English'),
                created_at,
                updated_at
            FROM projects
        SQL);

        DB::statement('DROP TABLE projects');
        DB::statement('ALTER TABLE projects_new RENAME TO projects');
        DB::statement('PRAGMA foreign_keys = ON');
    }
};
