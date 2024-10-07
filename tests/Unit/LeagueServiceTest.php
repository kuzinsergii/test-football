<?php
namespace Tests\Unit;

use App\Models\Game;
use Tests\TestCase;
use App\Services\LeagueService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionClass;
use App\Models\League;
use App\Models\Team;


class LeagueServiceTest extends TestCase
{
    use RefreshDatabase;

    protected LeagueService $leagueService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->leagueService = app(LeagueService::class);
    }

    /** @test */
    public function create_league_with_correct_number_of_teams()
    {
        $numberOfTeams = 4;
        $leagueData = $this->leagueService->createLeague($numberOfTeams);

        $this->assertEquals('success', $leagueData['status']);
        $this->assertCount($numberOfTeams, $leagueData['teams']);
    }

    /** @test */
    public function create_league_with_correct_number_of_teams_for_odd_team_count()
    {
        $numberOfTeams = 5;
        $leagueData = $this->leagueService->createLeague($numberOfTeams);

        $this->assertCount($numberOfTeams, $leagueData['teams']);
    }

    /** @test */
    public function it_generates_team_name_correctly()
    {
        $leagueService = new LeagueService();

        $reflection = new ReflectionClass($leagueService);
        $method = $reflection->getMethod('generateTeamName');
        $method->setAccessible(true);

        $result = $method->invoke($leagueService);

        $this->assertNotEmpty($result);
        $this->assertIsString($result);
    }
    public function test_calculate_standings()
    {
        $league = League::factory()->create();
        $teams = Team::factory()->count(4)->create(['league_id' => $league->id]);

        Game::factory()->create([
            'league_id' => $league->id,
            'team_a_id' => $teams[0]->id,
            'team_b_id' => $teams[1]->id,
            'score_team_a' => 3,
            'score_team_b' => 1,
            'played' => true,
        ]);

        Game::factory()->create([
            'league_id' => $league->id,
            'team_a_id' => $teams[2]->id,
            'team_b_id' => $teams[3]->id,
            'score_team_a' => 2,
            'score_team_b' => 2,
            'played' => true,
        ]);

        $leagueService = new LeagueService();
        $reflection = new ReflectionClass($leagueService);
        $method = $reflection->getMethod('calculateStandings');
        $method->setAccessible(true);

        $standings = $method->invoke($leagueService, $teams, 1);

        $this->assertIsArray($standings);
        $this->assertCount(4, $standings);
        $this->assertEquals(3, $standings[0]['points']);
        $this->assertEquals(1, $standings[0]['wins']);
        $this->assertEquals(1, $standings[0]['games_played']);
        $this->assertEquals(0, $standings[0]['draws']);
        $this->assertEquals(0, $standings[0]['losses']);
        $this->assertEquals(3, $standings[0]['goals_scored']);
        $this->assertEquals(1, $standings[0]['goals_conceded']);
    }

}
