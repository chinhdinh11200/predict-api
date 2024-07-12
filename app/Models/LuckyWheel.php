<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\SoftDeletes;

class LuckyWheel extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'lucky_wheels';

    public const NO_SPIN_AGAIN = 0;
    public const SPIN_AGAIN = 1;
    public const MISS_A_TURN = 1;

    /**
     * @var string[]
     */
    protected $fillable = [
        'name_vi',
        'name_en',
        'image_url',
        'slice_quantity',
        'prize_quantity',
        'winning_probability',
        'reward',
        'spin_again',
    ];

    public function ticketHistory(): HasMany
    {
        return $this->hasMany(TicketHistory::class, 'lucky_wheel_id', 'id');
    }
}
