<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Game extends Model
{
    use HasFactory;

    protected $fillable = ['league_id', 'team_a_id', 'team_b_id', 'week', 'score_team_a', 'score_team_b', 'played'];

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }

    public function teamA(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_a_id');
    }

    public function teamB(): BelongsTo
    {
        return $this->belongsTo(Team::class, 'team_b_id');
    }
}
