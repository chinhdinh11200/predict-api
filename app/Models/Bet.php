<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Bet extends Model
{
    protected $fillable = ['user_id', 'session_id', 'amount', 'is_demo', 'bet_type', 'is_result', 'result' , 'type', 'reward', 'is_marketing'];
    public const REAL_TYPE = 0;
    public const DEMO_TYPE = 1;

    public const NO_ACTION = 0;
    public const EXECUTED_RESULT = 1;

    public const SUB = 0;
    public const ADD = 1;

    public const WIN = 1;
    public const LOSE = 0;

    public const UP = 1;
    public const DOWN = 0;
    public const MARKETING = 1;
    public const NONE_MARKETING = 0;
    use HasFactory;

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function chart(): BelongsTo
    {
        return $this->belongsTo(Chart::class, 'session_id', 'session_id');
    }
}
