<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            if (! Schema::hasColumn('projects', 'business_type') && Schema::hasColumn('projects', 'industry')) {
                $table->string('business_type')->nullable()->after('industry');
            } elseif (! Schema::hasColumn('projects', 'business_type')) {
                $table->string('business_type')->nullable()->after('name');
            }

            if (! Schema::hasColumn('projects', 'description')) {
                $table->text('description')->nullable()->after('business_type');
            }

            if (! Schema::hasColumn('projects', 'stage')) {
                $table->string('stage')->nullable()->after('description');
            }

            if (! Schema::hasColumn('projects', 'employees')) {
                $table->string('employees')->nullable()->after('stage');
            }

            if (! Schema::hasColumn('projects', 'budget')) {
                $table->string('budget')->nullable()->after('employees');
            }

            if (! Schema::hasColumn('projects', 'market')) {
                $table->text('market')->nullable()->after('budget');
            }

            if (! Schema::hasColumn('projects', 'competitors')) {
                $table->text('competitors')->nullable()->after('market');
            }

            if (! Schema::hasColumn('projects', 'language')) {
                $table->string('language')->default('English')->after('competitors');
            }
        });

        if (Schema::hasColumn('projects', 'industry')) {
            DB::table('projects')
                ->whereNull('business_type')
                ->update([
                    'business_type' => DB::raw('industry'),
                ]);
        }
    }

    public function down(): void
    {
        Schema::table('projects', function (Blueprint $table): void {
            $columns = [
                'business_type',
                'description',
                'stage',
                'employees',
                'budget',
                'market',
                'competitors',
                'language',
            ];

            foreach ($columns as $column) {
                if (Schema::hasColumn('projects', $column)) {
                    $table->dropColumn($column);
                }
            }
        });
    }
};
