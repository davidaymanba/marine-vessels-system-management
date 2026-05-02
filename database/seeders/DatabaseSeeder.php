<?php

namespace Database\Seeders;

use App\Models\ExitGate;
use App\Models\Vessel;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class DatabaseSeeder extends Seeder
{
    use WithoutModelEvents;

    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::updateOrCreate(
            ['email' => 'admin@system.com'],
            [
                'name' => 'Admin',
                'phone' => '201001234567',
                'password' => 'admin123',
                'role' => 'admin',
            ]
        );

        collect([
            'البوابة الرئيسية',
            'المخرج الثاني',
            'المخرج البحري',
        ])->each(function (string $name) {
            ExitGate::updateOrCreate(
                ['name' => $name],
                [
                    'description' => null,
                    'is_active' => true,
                ]
            );
        });

        collect(range(1, 10))->each(function (int $index) {
            Vessel::updateOrCreate(
                ['vessel_number' => 'VSL-' . str_pad((string) $index, 3, '0', STR_PAD_LEFT)],
                [
                    'name' => 'وسيلة تجريبية ' . $index,
                    'barcode' => (string) Str::uuid(),
                    'status' => 'inside',
                    'description' => 'بيانات تجريبية',
                    'image' => null,
                ]
            );
        });
    }
}
