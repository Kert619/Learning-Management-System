<?php

namespace Database\Factories;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\User>
 */
class UserFactory extends Factory
{
    /**
     * The current password being used by the factory.
     */
    protected static ?string $password;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            // 'email' => 'kert@car.info',
            'email' => fake()->email(),
            'password' => static::$password ??= Hash::make('password')
        ];
    }

    public function default(): static
    {
        return $this->state(fn(array $attributes) => [
            'email' => 'kert@car.info',
            'role' => 'admin',
            'email_verified_at' => now()
        ]);
    }


    /**
     * Indicate that the model's email address should be unverified.
     */
    public function unverified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => null,
        ]);
    }

    /**
     * Indicate that the model's email address should be verified.
     */
    public function verified(): static
    {
        return $this->state(fn(array $attributes) => [
            'email_verified_at' => now(),
        ]);
    }

    /**
     * Set the user role to 'instructor'.
     */
    public function instructor(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'instructor',
        ]);
    }

    /**
     * Set the user role to 'admin'.
     */
    public function admin(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'admin',
        ]);
    }

    /**
     * Set the user role to 'student'.
     */
    public function student(): static
    {
        return $this->state(fn(array $attributes) => [
            'role' => 'student',
        ]);
    }
}
