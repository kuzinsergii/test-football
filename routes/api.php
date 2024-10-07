<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LeagueController;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::post('/create-league', [LeagueController::class, 'createLeague']);
Route::post('/league/{leagueId}/play-round/{week}', [LeagueController::class, 'playRound']);
