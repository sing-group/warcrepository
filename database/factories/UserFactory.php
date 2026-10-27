<?php

namespace Database\Factories;

use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * Sustituye a las definiciones 'registered', 'demo' y 'admin' de ModelFactory.php.
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\User>
 */
class UserFactory extends Factory
{
    protected $model = User::class;

    /**
     * Usuario 'registered' (por defecto).
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid,
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'role' => 'registered',
            'password' => bcrypt('secret'),
            'remember_token' => Str::random(10),
            'photo' => '',
            'verified' => 1,
        ];
    }

    public function demo(): static
    {
        return $this->state(fn () => [
            'name' => 'demo',
            'email' => 'demo@demo.demo',
            'role' => 'demo',
        ]);
    }

    public function admin(): static
    {
        return $this->state(fn () => [
            'name' => 'admin',
            'email' => 'admin@admin.admin',
            'role' => 'admin',
        ]);
    }
}
