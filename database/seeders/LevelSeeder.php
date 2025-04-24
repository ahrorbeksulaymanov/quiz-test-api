<?php

namespace Database\Seeders;

use App\Models\Level;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class LevelSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Level::create([
            'title' => "Oson",
            "ball" => 5
        ]);
        Level::create([
            'title' => "O'rtacha",
            "ball" => 10
        ]);
        Level::create([
            'title' => "Qiyin",
            "ball" => 15
        ]);
    }
}
