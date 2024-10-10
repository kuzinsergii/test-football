<?php
namespace App\Contracts;

use App\Models\League;

interface MatchGenServiceInterface
{
    public function generateRoundsTable(League $league, array $teams, int $numberOfTeams): array;
}
