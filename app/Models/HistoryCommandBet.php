<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class HistoryCommandBet extends Model
{
    use HasFactory;

    protected $table = 'history_command_bets';
    
    protected $fillable = [
        'session_id',
        'type',
        'type_target',
    ];

    public const COMMAND_REGULATION_TYPE = 1;
    public const COMMAND_REVERSE_TYPE = 0;

    public const BUY_TYPE = 1;
    public const SELL_TYPE = 0;
}
