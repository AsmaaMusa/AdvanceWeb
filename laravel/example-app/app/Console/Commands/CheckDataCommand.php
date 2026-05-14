<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckDataCommand extends Command
{
    protected $signature = 'check:data';
    protected $description = 'Check database data for testing';

    public function handle()
    {
        $this->info('=== DATABASE DATA CHECK ===');
        $this->newLine();

        $reportCount = DB::table('reports')->count();
        $projectCount = DB::table('projects')->count();
        $userCount = DB::table('users')->count();

        $this->info("📊 REPORTS: {$reportCount}");
        $this->info("📁 PROJECTS: {$projectCount}");
        $this->info("👥 USERS: {$userCount}");
        $this->newLine();

        $this->info('📈 REPORTS BY TYPE:');
        DB::table('reports')
            ->groupBy('type')
            ->select('type', DB::raw('count(*) as count'))
            ->get()
            ->each(fn($row) => $this->line("   ✓ {$row->type}: {$row->count}"));

        $this->newLine();
        $this->info('📝 REPORTS BY LANGUAGE:');
        DB::table('reports')
            ->groupBy('language')
            ->select('language', DB::raw('count(*) as count'))
            ->get()
            ->each(fn($row) => $this->line("   ✓ {$row->language}: {$row->count}"));

        $this->newLine();
        $this->info('✅ REPORTS BY STATUS:');
        DB::table('reports')
            ->groupBy('status')
            ->select('status', DB::raw('count(*) as count'))
            ->get()
            ->each(fn($row) => $this->line("   ✓ {$row->status}: {$row->count}"));

        $this->newLine();
        $this->info('🏢 TOP 5 PROJECTS BY REPORT COUNT:');
        \App\Models\Project::withCount('reports')
            ->orderByDesc('reports_count')
            ->limit(5)
            ->get()
            ->each(fn($p) => $this->line("   ✓ {$p->name}: {$p->reports_count} reports"));

        $this->newLine();
        if ($reportCount === 0) {
            $this->warn('⚠️  No reports found! Run seeders to generate test data:');
            $this->line('   php artisan db:seed');
        } else {
            $this->line('✅ Data is ready for testing!');
        }
    }
}
