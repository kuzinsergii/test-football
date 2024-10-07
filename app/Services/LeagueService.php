<?php
namespace App\Services;

use App\Models\League;
use App\Models\Team;
use App\Models\Game;
use Illuminate\Support\Str;
use App\Enums\Nouns;
use App\Enums\Adjectives;
use App\Http\Resources\TeamResource;
use App\Http\Resources\RoundResource;
use App\Http\Resources\GameResource;

class LeagueService
{
    public function createLeague(int $numberOfTeams): array
    {
        $league = League::factory()->create();

        $teams = [];

        for ($i = 0; $i < $numberOfTeams; $i++) {
            $team = Team::factory()->create([
                'league_id' => $league->id,
            ]);

            $teams[] = $team;
        }

        $isOdd = $numberOfTeams % 2 !== 0;
        if ($isOdd) { //need to add bye command
            $teams[] = null;
            $numberOfTeams++;
        }

        // using Round Robin algorithm
        $rounds = $this->generateRounds($league, $teams, $numberOfTeams);
        // and same for reverse matches, if needed
        $reverseRounds = $this->generateRounds($league, $teams, $numberOfTeams, true);

        $rounds = array_merge($rounds, $reverseRounds);

        return [
            'status' => 'success',
            'league_id' => $league->id,
            'league_name' => $league->name,
            'teams' => TeamResource::collection($league->teams),
            'rounds' => $rounds,
            'next_round' => 1,
        ];
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
                    if ($reverse) {
                        $game = Game::factory()->create([
                            'league_id' => $league->id,
                            'team_a_id' => $teamB->id,
                            'team_b_id' => $teamA->id,
                            'week' => $week + $numberOfWeeks,
                        ]);
                    } else {
                        $game = Game::factory()->create([
                            'league_id' => $league->id,
                            'team_a_id' => $teamA->id,
                            'team_b_id' => $teamB->id,
                            'week' => $week,
                        ]);
                    }

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

        $teams = Team::where('league_id', $leagueId)->get();
        $standings = $this->calculateStandings($teams, $week);

        return [
            'status' => 'success',
            'games' => GameResource::collection($games),
            'standings' => $standings,
        ];
    }

    private function generateTeamName(): string
    {
        $useAdjective = rand(0, 1);
        $teamName = '';

        if ($useAdjective) {
            $teamName .= Adjectives::getRandomValue() . ' ';
        }

        $teamName .= Nouns::getRandomValue();

        return trim($teamName);
    }

    private function calculateStandings($teams, $week): array
    {
        $standings = [];

        foreach ($teams as $team) {
        $games = Game::where(function ($query) use ($team) {
            $query->where('team_a_id', $team->id)
                      ->orWhere('team_b_id', $team->id);
            })
            ->where('played', true)
            ->where('week', '<=', $week)
            ->get();

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
}
