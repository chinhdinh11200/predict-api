<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class UserBlockBet extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'user_block_bets';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'session_id',
    ];
}
