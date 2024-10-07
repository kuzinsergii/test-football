<?php

namespace Database\Factories;

use App\Enums\Adjectives;
use App\Enums\Nouns;
use App\Models\Team;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Team>
 */
class TeamFactory extends Factory
{
    protected $model = Team::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        {
            $useAdjective = rand(0, 1);
            $teamName = '';

            if ($useAdjective) {
                $teamName .= Adjectives::getRandomValue() . ' ';
            }

            $teamName .= Nouns::getRandomValue();

            return [
                'name' => trim($teamName),
                'league_id' => null,
                'strength' => $this->faker->randomFloat(2, 0.4, 1.0),
            ];
        }
    }
}
