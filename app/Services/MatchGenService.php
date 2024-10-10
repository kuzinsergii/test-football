<?php
namespace App\Services;

use App\Contracts\MatchGenServiceInterface;
use App\Models\League;
use App\Models\Game;
use App\Http\Resources\RoundResource;
use App\Http\Resources\GameResource;
class MatchGenService implements MatchGenServiceInterface
{

    public function generateRoundsTable($league, $teams, $numberOfTeams): array
    {
        // using Round Robin algorithm
        $rounds = $this->generateRounds($league, $teams, $numberOfTeams);
        $reverseRounds = $this->generateRounds($league, $teams, $numberOfTeams, true);

        return array_merge($rounds, $reverseRounds);
    }

    private function generateRounds(League $league, array $teams, int $numberOfTeams, bool $reverse = false): array
    {
        $numberOfWeeks = $numberOfTeams - 1;
        $rounds = [];

        for ($week = 1; $week <= $numberOfWeeks; $week++) {
            $matches = [];

            for ($i = 0; $i < $numberOfTeams / 2; $i++) {
                $teamA = $teams[$i];
                $teamB = $teams[$numberOfTeams - 1 - $i];

                if ($teamA !== null && $teamB !== null) {
                    $game = Game::factory()->create([
                        'league_id' => $league->id,
                        'team_a_id' => $reverse ? $teamB->id : $teamA->id,
                        'team_b_id' => $reverse ? $teamA->id : $teamB->id,
                        'week' => $reverse ? $week + $numberOfWeeks : $week,
                    ]);

                    $matches[] = new GameResource($game);
                }
            }

            $roundData = ['week' => $reverse ? $week + $numberOfWeeks : $week, 'matches' => $matches];
            $rounds[] = new RoundResource($roundData);

            $lastTeam = array_pop($teams);
            array_splice($teams, 1, 0, [$lastTeam]);
        }

        return $rounds;
    }
}
