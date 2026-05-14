<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (Schema::hasColumn('projects', 'industry')) {
            DB::table('projects')
                ->whereNull('business_type')
                ->orWhere('business_type', '')
                ->update([
                    'business_type' => DB::raw('COALESCE(NULLIF(industry, \'\'), \'General\')'),
                ]);

            return;
        }

        DB::table('projects')
            ->whereNull('business_type')
            ->orWhere('business_type', '')
            ->update([
                'business_type' => 'General',
            ]);
    }

    public function down(): void
    {
        //
    }
};
