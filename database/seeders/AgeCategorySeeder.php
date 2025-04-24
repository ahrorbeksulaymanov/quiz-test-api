<?php

namespace Database\Seeders;

use App\Models\AgeCategory;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AgeCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        AgeCategory::create([
            'title' => "Bolalar",
            'from' => 7,
            'to' => 14,
        ]);
        AgeCategory::create([
            'title' => "O'rta yoshli",
            'from' => 15,
            'to' => 25,
        ]);
        AgeCategory::create([
            'title' => "Kattalar",
            'from' => 25,
            'to' => 99,
        ]);
    }
}
