<?php
namespace App\Services;

use App\Contracts\LeagueCreationServiceInterface;
use App\Contracts\MatchGenServiceInterface;
use App\Contracts\RoundCalculatorServiceInterface;
use App\Models\League;
use App\Models\Team;
use App\Enums\Nouns;
use App\Enums\Adjectives;

class LeagueCreationService implements LeagueCreationServiceInterface
{
    public function __construct(
        protected MatchGenServiceInterface $matchGenService,
        protected RoundCalculatorServiceInterface $roundCalculatorService,
    ) {
    }

    public function createLeague(int $numberOfTeams): array
    {
        $league = League::factory()->create();

        $teams = [];
        for ($i = 0; $i < $numberOfTeams; $i++) {
            $teamName = $this->generateTeamName();

            $team = Team::factory()->create([
                'name' => $teamName,
                'league_id' => $league->id,
                'strength' => rand(1, 100) / 100,
            ]);

            $teams[] = $team;
        }

        // "bye" team
        if ($numberOfTeams % 2 !== 0) {
            $teams[] = null;
            $numberOfTeams++;
        }

        $rounds = $this->matchGenService->generateRoundsTable($league, $teams, $numberOfTeams);
        $standings = $this->roundCalculatorService->calculateStandings($teams, 0);

        return [
            'status' => 'success',
            'league_id' => $league->id,
            'league_name' => $league->name,
            'standings' => $standings,
            'rounds' => $rounds,
            'next_round' => 1,
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
}
