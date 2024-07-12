<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class UserSetting extends Model
{
    use HasFactory;

    protected $table = 'user_settings';
    protected $fillable = [
        'user_id',
        'is_revert',
        'is_marketing',
        'is_lock_transaction',
        'open_volume',
        'show_balance',
    ];

    public const MARKETING = 1;
    public const NONE_MARKETING = 0;
    public const REVERT = 1;
    public const NONE_REVERT = 0;
    public const LOCK_TRANSACTION = 1;
    public const NONE_LOCK_TRANSACTION = 0;
    public const OPEN_VOLUME = 1;
    public const CLOSE_VOLUME = 0;
    public const SHOW_BALANCE = 1;
    public const HIDDEN_BALANCE = 0;

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
