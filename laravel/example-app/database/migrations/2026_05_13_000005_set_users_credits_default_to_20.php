<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('users', function ($table): void {
                $table->unsignedInteger('credits')->default(20)->change();
            });

            return;
        }

        $this->rebuildUsersTable(20);
    }

    public function down(): void
    {
        if (DB::getDriverName() !== 'sqlite') {
            Schema::table('users', function ($table): void {
                $table->unsignedInteger('credits')->default(0)->change();
            });

            return;
        }

        $this->rebuildUsersTable(0);
    }

    private function rebuildUsersTable(int $creditsDefault): void
    {
        DB::statement('PRAGMA foreign_keys = OFF');

        DB::statement(sprintf(<<<'SQL'
            CREATE TABLE users_new (
                id INTEGER PRIMARY KEY AUTOINCREMENT NOT NULL,
                name varchar NOT NULL,
                email varchar NOT NULL,
                email_verified_at datetime NULL,
                password varchar NOT NULL,
                remember_token varchar NULL,
                role varchar NOT NULL DEFAULT 'user',
                credits INTEGER NOT NULL DEFAULT '%d',
                stripe_customer_id varchar NULL,
                projects_count INTEGER NOT NULL DEFAULT '0',
                is_active tinyint(1) NOT NULL DEFAULT '1',
                api_token varchar(64) NULL,
                last_admin_login_at datetime NULL,
                created_at datetime NULL,
                updated_at datetime NULL
            )
        SQL, $creditsDefault));

        DB::statement(<<<'SQL'
            INSERT INTO users_new (
                id,
                name,
                email,
                email_verified_at,
                password,
                remember_token,
                role,
                credits,
                stripe_customer_id,
                projects_count,
                is_active,
                api_token,
                last_admin_login_at,
                created_at,
                updated_at
            )
            SELECT
                id,
                name,
                email,
                email_verified_at,
                password,
                remember_token,
                role,
                credits,
                stripe_customer_id,
                projects_count,
                is_active,
                api_token,
                last_admin_login_at,
                created_at,
                updated_at
            FROM users
        SQL);

        DB::statement('DROP TABLE users');
        DB::statement('ALTER TABLE users_new RENAME TO users');
        DB::statement('CREATE UNIQUE INDEX users_email_unique ON users (email)');
        DB::statement('CREATE UNIQUE INDEX users_api_token_unique ON users (api_token)');
        DB::statement('PRAGMA foreign_keys = ON');
    }
};
