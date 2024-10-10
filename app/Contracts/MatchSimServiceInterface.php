<?php
namespace App\Contracts;

interface MatchSimServiceInterface
{
    public function playRound(int $leagueId, int $week): array;
}
