<?php

namespace Database\Seeders;

use App\Models\CreditPackage;
use Illuminate\Database\Seeder;

class CreditPackageSeeder extends Seeder
{
    public function run(): void
    {
        $packages = [
            ['name' => 'Starter', 'price' => 9, 'credits' => 100, 'is_active' => true],
            ['name' => 'Growth', 'price' => 29, 'credits' => 400, 'is_active' => true],
            ['name' => 'Pro', 'price' => 79, 'credits' => 1200, 'is_active' => true],
        ];

        foreach ($packages as $package) {
            CreditPackage::query()->updateOrCreate(
                ['name' => $package['name']],
                $package,
            );
        }
    }
}
