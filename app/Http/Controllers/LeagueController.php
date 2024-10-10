<?php
namespace App\Http\Controllers;

use App\Contracts\LeagueCreationServiceInterface;
use App\Contracts\MatchSimServiceInterface;
use Illuminate\Http\Request;

class LeagueController extends Controller
{

    public function __construct(
        protected LeagueCreationServiceInterface $leagueCreationService,
        protected MatchSimServiceInterface $matchSimService
    ) {
    }

    public function createLeague(Request $request)
    {
        $request->validate([
            'number_of_teams' => 'required|integer|min:2|max:255',
        ]);

        $leagueData = $this->leagueCreationService->createLeague($request->input('number_of_teams'));

        return response()->json($leagueData, 201);
    }

    public function playRound(Request $request, int $leagueId, int $week)
    {
        $result = $this->matchSimService->playRound($leagueId, $week);

        return response()->json($result, 200);
    }

}
