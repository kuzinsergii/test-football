<?php
namespace App\Contracts;

interface LeagueCreationServiceInterface
{
    public function createLeague(int $numberOfTeams): array;
}
