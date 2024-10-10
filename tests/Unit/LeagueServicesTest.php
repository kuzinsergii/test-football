<?php
namespace Tests\Unit;

use App\Contracts\LeagueCreationServiceInterface;
use App\Contracts\RoundCalculatorServiceInterface;
use App\Models\Game;
use Tests\TestCase;
use App\Services\LeagueService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use ReflectionClass;
use App\Models\League;
use App\Models\Team;


class LeagueServicesTest extends TestCase
{

    protected LeagueCreationServiceInterface $leagueCreationService;
    protected RoundCalculatorServiceInterface $roundCalculatorService;

    protected function setUp(): void
    {
        parent::setUp();
        $this->mock(Team::class, function ($mock) {
            $mock->shouldReceive('factory->create')->andReturn((object)[
                'id' => 1,
                'name' => 'Mock Team',
                'league_id' => 1,
                'strength' => 0.75,
            ]);
        });
        $this->leagueCreationService = app(LeagueCreationServiceInterface::class);
        $this->roundCalculatorService = app(RoundCalculatorServiceInterface::class);
    }

    /**
     * test for LeagueCreationService
     * @dataProvider teamsCountCaseDataProvider
     */
    public function test_create_league_with_correct_number_of_teams($numberOfTeams)
    {
        $leagueData = $this->leagueCreationService->createLeague($numberOfTeams);
        $this->assertEquals('success', $leagueData['status']);
        $this->assertNotEmpty($leagueData['league_name']);
    }

    public function test_generates_team_name_correctly()
    {
        $reflection = new ReflectionClass($this->leagueCreationService );
        $method = $reflection->getMethod('generateTeamName');
        $method->setAccessible(true);

        $result = $method->invoke($this->leagueCreationService);

        $this->assertNotEmpty($result);
        $this->assertIsString($result);
    }

    public static function teamsCountCaseDataProvider()
    {
        return [
            'two teams' => [2],
            'four teams' => [3],
            'five teams' => [4],
        ];
    }


    /**
     * test for RoundCalculatorService
     */
    public function testCalculateStandings()
    {
        $gameMock = \Mockery::mock(Game::class);
        $gameMock->shouldReceive('where->where->where->get')->andReturn(collect([
            (object)[
                'team_a_id' => 1,
                'team_b_id' => 2,
                'score_team_a' => 3,
                'score_team_b' => 1,
                'played' => true,
            ],
            (object)[
                'team_a_id' => 2,
                'team_b_id' => 1,
                'score_team_a' => 2,
                'score_team_b' => 2,
                'played' => true,
            ]
        ]));

        $teams = [
            (object)['id' => 1, 'name' => 'Team A'],
            (object)['id' => 2, 'name' => 'Team B']
        ];

        $result = $this->roundCalculatorService->calculateStandings($teams, 5);

        $this->assertIsArray($result);
        $this->assertCount(2, $result);
        $this->assertEquals('Team A', $result[0]['team']->name);
    }




}
