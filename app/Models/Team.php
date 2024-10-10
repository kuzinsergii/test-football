<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Team extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'league_id', 'strength'];

    public function league(): BelongsTo
    {
        return $this->belongsTo(League::class);
    }
}
