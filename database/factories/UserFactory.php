<?php

declare(strict_types=1);

namespace Database\Factories;

use HopsWeb\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

/**
 * @extends Factory<User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    public function definition(): array
    {
        return [
            "name" => fake()->name(),
            "email" => fake()->unique()->safeEmail(),
            "email_verified_at" => now(),
            "password" => Hash::make("password"),
            "remember_token" => Str::random(10),
            "is_admin" => false,
            "is_team_member" => false,
        ];
    }

    public function unverified(): static
    {
        return $this->state(fn(array $attributes): array => [
            "email_verified_at" => null,
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn(array $attributes): array => [
            "is_admin" => true,
        ]);
    }

    public function teamMember(): static
    {
        return $this->state(fn(array $attributes): array => [
            "is_team_member" => true,
        ]);
    }
}
