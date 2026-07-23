<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AssetCategorySeeder extends Seeder
{
    public function run(): void
    {
        DB::table('T21_asset_categories')->insert([
            [
                'T21_id' => (string) Str::ulid(),
                'T21_name' => 'Laptop',
                'T21_created_at' => now(),
                'T21_updated_at' => now(),
            ],
            [
                'T21_id' => (string) Str::ulid(),
                'T21_name' => 'Monitor',
                'T21_created_at' => now(),
                'T21_updated_at' => now(),
            ],
            [
                'T21_id' => (string) Str::ulid(),
                'T21_name' => 'Projector',
                'T21_created_at' => now(),
                'T21_updated_at' => now(),
            ],
        ]);
    }
}
