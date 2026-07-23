<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\AssetCategory;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\DB;

class AssetCategorySeeder extends Seeder
{
    public function run(): void
    {
        $categories = ['Laptop','Monitor','Projector'];
        foreach ($categories as $name) {
            AssetCategory::firstOrCreate(['T21_name' => $name]);
        }
    }
}
