<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\LeagueService;

class LeagueController extends Controller
{
    protected LeagueService $leagueService;

    public function __construct(LeagueService $leagueService)
    {
        $this->leagueService = $leagueService;
    }

    public function createLeague(Request $request)
    {
        $request->validate([
            'number_of_teams' => 'required|integer|min:2|max:255',
        ]);

        $leagueData = $this->leagueService->createLeague($request->input('number_of_teams'));

        return response()->json($leagueData, 201);
    }

    public function playRound(Request $request, int $leagueId, int $week)
    {
        $result = $this->leagueService->playRound($leagueId, $week);

        return response()->json($result, 200);
    }

}
