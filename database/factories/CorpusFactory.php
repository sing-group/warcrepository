<?php

namespace Database\Factories;

use App\Corpus;
use App\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Str;

/**
 * Sustituye a la definición 'corpus' de ModelFactory.php.
 *
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Corpus>
 */
class CorpusFactory extends Factory
{
    protected $model = Corpus::class;

    /**
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'uuid' => $this->faker->uuid,
            'name' => $this->faker->name,
            'path' => Str::random(40),
            'user_id' => User::factory(),
        ];
    }
}
