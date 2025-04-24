<?php

namespace Database\Factories;

use App\Models\AgeCategory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Test>
 */
class TestFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        static $order = 1;

        return [
            'title' => fake()->word(2),
            'description' => fake()->paragraph(1),
            'order' => $order++,
            'ball' => 100,
            'duration' => 10,
            'age_category_id' => AgeCategory::inRandomOrder()->first()->id,
        ];
    }
}
