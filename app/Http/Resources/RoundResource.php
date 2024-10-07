<?php
namespace App\Http\Resources;

use Illuminate\Http\Resources\Json\JsonResource;

class RoundResource extends JsonResource
{
    public function toArray($request)
    {
        return [
            'week' => $this['week'],
            'matches' => GameResource::collection($this['matches']),
        ];
    }
}
