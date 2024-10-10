<?php
namespace App\Services;

use App\Contracts\MatchSimServiceInterface;
use App\Contracts\RoundCalculatorServiceInterface;
use App\Models\Team;
use App\Models\Game;
use App\Http\Resources\GameResource;

class MatchSimService implements MatchSimServiceInterface
{
    public function __construct(
        protected RoundCalculatorServiceInterface $roundCalculatorService,
    ) {
    }
    public function playRound(int $leagueId, int $week): array
    {
        $games = Game::where('league_id', $leagueId)
            ->where('week', '<=', $week)
            ->where('played', false)
            ->get();

        foreach ($games as $game) {
            $teamA = $game->teamA;
            $teamB = $game->teamB;

            if ($teamA && $teamB) {
                $strengthA = $teamA->strength;
                $strengthB = $teamB->strength;
                $totalStrength = $strengthA + $strengthB;

                $drawProbability = 0.2;
                $winProbabilityA = ($strengthA / $totalStrength) * (1 - $drawProbability);
                $random = rand(0, 100) / 100;

                if ($random < $drawProbability) {
                    $scoreTeamA = $scoreTeamB = rand(0, 3);
                } elseif ($random < $drawProbability + $winProbabilityA) {
                    $scoreTeamA = rand(1, 5);
                    $scoreTeamB = rand(0, $scoreTeamA - 1);
                } else {
                    $scoreTeamB = rand(1, 5);
                    $scoreTeamA = rand(0, $scoreTeamB - 1);
                }

                $game->update([
                    'score_team_a' => $scoreTeamA,
                    'score_team_b' => $scoreTeamB,
                    'played' => true,
                ]);
            }
        }

        $maxRounds = Game::where('league_id', $leagueId)->orderBy('week', 'desc')->limit(1)->value('week');
        $nextRound = $week < $maxRounds ? $week + 1 : 0;

        $teams = Team::where('league_id', $leagueId)->get();
        $standings = $this->roundCalculatorService->calculateStandings($teams, $week);

        return [
            'status' => 'success',
            'games' => GameResource::collection($games),
            'standings' => $standings,
            'next_round' => $nextRound,
        ];
    }
}
