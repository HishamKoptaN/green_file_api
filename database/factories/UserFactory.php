<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

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
            'password' => static::$password ??= Hash::make('password'),
            'status' => fake()->randomElement(['active', 'inactive']),
            'token' => sha1(str()->random(20)),
            'image' => 'default.png',
            'address' => fake()->address(),
            'phone' => fake()->phoneNumber(),
            'verified_at' => now(),
            'inactivate_end_at' => null,
            'message' => null,
            'refered_by' => (User::inRandomOrder()->first())->id
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
