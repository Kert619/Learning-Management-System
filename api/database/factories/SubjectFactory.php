<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Subject>
 */
class SubjectFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'subject_code' => fake()->randomLetter() . fake()->randomLetter() . fake()->randomLetter() . fake()->randomLetter(),
            'subject_title' => fake()->sentence()
        ];
    }
}
