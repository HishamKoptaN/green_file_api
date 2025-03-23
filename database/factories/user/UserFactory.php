<?php

namespace Database\Factories\user;

use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use App\Models\User\User;

class UserFactory extends Factory
{
    protected static ?string $password;


    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'username' => fake()->userName(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'status' => fake()->randomElement(
                [
                    'active',
                    'inactive',
                ],
            ),
            'verified_at' => now(),
            'inactivate_end_at' => null,
        ];
    }
    public function unverified(): static
    {
        return $this->state(
            fn(array $attributes) => [
                'email_verified_at' => null,
            ],
        );
    }
}


