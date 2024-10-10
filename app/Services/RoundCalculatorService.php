<?php
namespace App\Services;

use App\Contracts\RoundCalculatorServiceInterface;
use App\Models\Game;
use App\Http\Resources\TeamResource;

class RoundCalculatorService implements RoundCalculatorServiceInterface
{
    public function calculateStandings($teams, $week): array
    {
        $standings = [];

        foreach ($teams as $team) {
            if (empty($team->id)) {
                continue;
            }
            $games = $this->getTeamGames($team, $week);

            $points = 0;
            $gamesPlayed = 0;
            $goalsScored = 0;
            $goalsConceded = 0;
            $wins = 0;
            $draws = 0;
            $losses = 0;

            foreach ($games as $game) {
                if ($game->team_a_id == $team->id) {
                    $goalsScored += $game->score_team_a;
                    $goalsConceded += $game->score_team_b;
                    $gamesPlayed++;

                    if ($game->score_team_a > $game->score_team_b) {
                        $points += 3;
                        $wins++;
                    } elseif ($game->score_team_a == $game->score_team_b) {
                        $points += 1;
                        $draws++;
                    } else {
                        $losses++;
                    }
                } elseif ($game->team_b_id == $team->id) {
                    $goalsScored += $game->score_team_b;
                    $goalsConceded += $game->score_team_a;
                    $gamesPlayed++;

                    if ($game->score_team_b > $game->score_team_a) {
                        $points += 3;
                        $wins++;
                    } elseif ($game->score_team_b == $game->score_team_a) {
                        $points += 1;
                        $draws++;
                    } else {
                        $losses++;
                    }
                }
            }

            $standings[] = [
                'team' => new TeamResource($team),
                'points' => $points,
                'games_played' => $gamesPlayed,
                'goals_scored' => $goalsScored,
                'goals_conceded' => $goalsConceded,
                'wins' => $wins,
                'draws' => $draws,
                'losses' => $losses,
            ];
        }

        usort($standings, function ($a, $b) {
            return $b['points'] <=> $a['points'];
        });

        return $standings;
    }

    private function getTeamGames($team, $week)
    {
        return Game::where(function ($query) use ($team) {
            $query->where('team_a_id', $team->id)
                ->orWhere('team_b_id', $team->id);
        })->where('played', true)
            ->where('week', '<=', $week)
            ->get();
    }
}
