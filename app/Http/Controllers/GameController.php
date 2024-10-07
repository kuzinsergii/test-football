<?php

namespace App\Http\Controllers;

use App\Enums\Adjectives;
use App\Enums\Nouns;
use App\Models\League;
use App\Models\Team;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class GameController extends Controller
{
    public function createLeague(Request $request)
    {

        $request->validate([
            'number_of_teams' => 'required|integer|min:2|max:255',
        ]);

        // Создание новой лиги
        $league = League::create([
            'name' => 'League ' . Str::uuid(),
            'created_at' => now(),
        ]);

        // Генерация команд для лиги
        $numberOfTeams = $request->input('number_of_teams');
        $nouns = Nouns::values();
        $adjectives = Adjectives::values();

        for ($i = 0; $i < $numberOfTeams; $i++) {
            $teamName = $this->generateTeamName($nouns, $adjectives);

            Team::create([
                'name' => $teamName,
                'league_id' => $league->id,
                'strength' => rand(1, 100) / 100, // Power of team, from 0.1 to 1
            ]);
        }

        return response()->json(['status' => 'success', 'league_id' => $league->id], 201);
    }

    private function generateTeamName($nouns, $adjectives)
    {
        $useAdjective = rand(0, 1);
        $useNoun = rand(0, 1);

        $teamName = '';

        if ($useAdjective) {
            $teamName .= $adjectives[array_rand($adjectives)] . ' ';
        }

        if ($useNoun || !$useAdjective) {
            $teamName .= $nouns[array_rand($nouns)];
        }

        return trim($teamName);
    }
}
