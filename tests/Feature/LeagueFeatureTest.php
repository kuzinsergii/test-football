<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class LeagueFeatureTest extends TestCase
{
    use RefreshDatabase;

    /**
     * @test
     * @dataProvider leagueDataProvider
     */
    public function create_a_league($numberOfTeams)
    {
        $response = $this->postJson('/api/create-league', [
            'number_of_teams' => $numberOfTeams,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'league_id' => true,
                'league_name' => true,
                'next_round' => 1,
            ]);

        $responseData = $response->json();

        // Check standings
        $this->assertArrayHasKey('standings', $responseData);
        $this->assertCount($numberOfTeams, $responseData['standings']);

        foreach ($responseData['standings'] as $team) {
            $this->assertArrayHasKey('team', $team);
            $this->assertArrayHasKey('points', $team);
            $this->assertArrayHasKey('games_played', $team);
            $this->assertArrayHasKey('goals_scored', $team);
            $this->assertArrayHasKey('goals_conceded', $team);
            $this->assertArrayHasKey('wins', $team);
            $this->assertArrayHasKey('draws', $team);
            $this->assertArrayHasKey('losses', $team);
        }

        // Check rounds
        $this->assertArrayHasKey('rounds', $responseData);
        $this->assertGreaterThanOrEqual(1, count($responseData['rounds']));
        foreach ($responseData['rounds'] as $round) {
            $this->assertArrayHasKey('week', $round);
            $this->assertArrayHasKey('matches', $round);
        }
    }

    /** @test */
    public function validate_number_of_teams()
    {
        $response = $this->postJson('/api/create-league', [
            'number_of_teams' => 1,
        ]);

        $response->assertStatus(422);
    }

    public static function leagueDataProvider()
    {
        return [
            'three teams' => [3],
            'four teams' => [4],
        ];
    }
}
