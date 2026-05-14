#!/usr/bin/env php
<?php
// Check database data

require_once __DIR__ . '/bootstrap/app.php';

$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\DB;

echo "\n=== DATABASE DATA CHECK ===\n\n";

echo "📊 REPORTS COUNT: " . DB::table('reports')->count() . "\n";
echo "📁 PROJECTS COUNT: " . DB::table('projects')->count() . "\n";
echo "👥 USERS COUNT: " . DB::table('users')->count() . "\n\n";

echo "📈 REPORTS BY TYPE:\n";
DB::table('reports')->groupBy('type')->select('type', DB::raw('count(*) as count'))
    ->get()->each(function($row) {
        echo "   - {$row->type}: {$row->count}\n";
    });

echo "\n📝 REPORTS BY LANGUAGE:\n";
DB::table('reports')->groupBy('language')->select('language', DB::raw('count(*) as count'))
    ->get()->each(function($row) {
        echo "   - {$row->language}: {$row->count}\n";
    });

echo "\n✅ REPORTS BY STATUS:\n";
DB::table('reports')->groupBy('status')->select('status', DB::raw('count(*) as count'))
    ->get()->each(function($row) {
        echo "   - {$row->status}: {$row->count}\n";
    });

echo "\n🏢 TOP PROJECTS BY REPORT COUNT:\n";
DB::table('projects')
    ->withCount('reports')
    ->orderByDesc('reports_count')
    ->limit(5)
    ->get()
    ->each(function($project) {
        echo "   - {$project->name}: {$project->reports_count} reports\n";
    });

echo "\n";
