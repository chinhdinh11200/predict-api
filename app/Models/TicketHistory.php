<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TicketHistory extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'ticket_history';

    public const TYPE_BUY = 0;
    public const TYPE_USE = 1;
    public const TYPE_REFUND = 2;

    protected $fillable = [
        'user_id',
        'quantity',
        'prize',
        'value',
        'type',
        'lucky_wheel_id',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function luckyWheel(): BelongsTo
    {
        return $this->belongsTo(LuckyWheel::class, 'lucky_wheel_id', 'id');
    }
}
