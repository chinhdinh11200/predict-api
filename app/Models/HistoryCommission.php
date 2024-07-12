<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class HistoryCommission extends Model
{
    protected $fillable = ['type', 'user_id', 'from_user_id', 'value', 'note'];
    public const TYPE_BET_COMMISSION = 1;
    public const TYPE_VIP_COMMISSION = 2;
    public const TYPE_NORMAL_COMMISSION = 3;

    public const COMMISSION_TYPE = [
        self::TYPE_BET_COMMISSION,
        self::TYPE_VIP_COMMISSION,
        self::TYPE_NORMAL_COMMISSION,
    ];
    
    use HasFactory;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    public function fromUser(): BelongsTo
    {
        return $this->belongsTo(User::class, 'from_user_id', 'id');
    }

    public function getCreatedAtFormattedAttribute()
    {
        return $this->created_at->format(config('date.fe_date_time_format'));
    }
}
