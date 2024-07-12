<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Chart extends Model
{
    protected $fillable = ['open_price', 'close_price', 'low_price', 'high_price', 'session_id', 'start_time', 'end_time', 'volume'];
    use HasFactory;

    public function bets(): HasMany
    {
        return $this->hasMany(Bet::class, 'session_id', 'session_id');
    }

    public function lastResult()
    {
        return $this->belongsTo(LastResult::class, 'session_id', 'id');
    }
}
