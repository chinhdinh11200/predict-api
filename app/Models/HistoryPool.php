<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoryPool extends Model
{
    protected $fillable = ['user_id', 'bet_id', 'value', 'type'];

    public const TYPE_BET = 1;
    public const TYPE_PAY = 2;
    public const TYPE = [
        self::TYPE_BET,
        self::TYPE_PAY,
    ];
    
    use HasFactory;

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
