<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->unsignedInteger('credits')->default(20)->after('role');
            $table->string('stripe_customer_id')->nullable()->after('credits');
            $table->unsignedInteger('projects_count')->default(0)->after('stripe_customer_id');
            $table->boolean('is_active')->default(true)->after('projects_count');
        });
    }

    public function down(): void
    {
        Schema::table('users', function (Blueprint $table): void {
            $table->dropColumn(['credits', 'stripe_customer_id', 'projects_count', 'is_active']);
        });
    }
};
