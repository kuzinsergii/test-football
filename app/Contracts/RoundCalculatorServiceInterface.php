<?php
namespace App\Contracts;


interface RoundCalculatorServiceInterface
{
    public function calculateStandings($teams, $week): array;
}
