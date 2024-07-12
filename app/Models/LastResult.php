<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LastResult extends Model
{
    protected $fillable = ['start_time', 'end_time', 'result', 'is_bet_session'];

    public const UP = 1;
    public const DOWN = 0;
    public const BET = 1;
    public const NO_BET = 0;
    use HasFactory;

    public function chart()
    {
        return $this->hasOne(Chart::class, 'session_id', 'id');
    }
}
