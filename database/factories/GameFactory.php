<?php

namespace Database\Factories;

use App\Models\Game;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends \Illuminate\Database\Eloquent\Factories\Factory<\App\Models\Game>
 */
class GameFactory extends Factory
{
    protected $model = Game::class;

    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        return [
            'league_id' => null,
            'team_a_id' => null,
            'team_b_id' => null,
            'week' => 1,
            'score_team_a' => null,
            'score_team_b' => null,
            'played' => false,
        ];
    }
}
