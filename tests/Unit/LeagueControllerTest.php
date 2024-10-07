<?php
namespace Tests\Feature;

use Tests\TestCase;
use Illuminate\Foundation\Testing\RefreshDatabase;

class LeagueControllerTest extends TestCase
{
    use RefreshDatabase;

    /** @test */
    public function create_a_league()
    {
        $response = $this->postJson('/api/create-league', [
            'number_of_teams' => 4,
        ]);

        $response->assertStatus(201)
            ->assertJson([
                'status' => 'success',
                'league_id' => true,
                'teams' => true,
            ]);
    }

    /** @test */
    public function validate_number_of_teams()
    {
        $response = $this->postJson('/api/create-league', [
            'number_of_teams' => 1,
        ]);

        $response->assertStatus(422);
    }
}
