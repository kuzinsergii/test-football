<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class GameResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'team_a' => new TeamResource($this->teamA),
            'team_b' => new TeamResource($this->teamB),
            'week' => $this->week,
            'score_team_a' => $this->score_team_a,
            'score_team_b' => $this->score_team_b,
            'played' => $this->played,
        ];
    }
}
