<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class TransactionDetail extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'transaction_details';

    public const TRANSACTION_TYPE_INTERNAL_DEPOSIT = 1;
    public const TRANSACTION_TYPE_INTERNAL_WITHDRAW = 2;
    public const TRANSACTION_TYPE_DEPOSIT = 3;
    public const TRANSACTION_TYPE_WITHDRAW = 4;
    public const TRANSACTION_TYPE_BUY_NORMAL = 5;
    public const TRANSACTION_TYPE_BUY_VIP = 6;

    public const TRANSACTION_TYPE_COMMISSION = [
        self::TRANSACTION_TYPE_BUY_NORMAL,
        self::TRANSACTION_TYPE_BUY_VIP,
    ];

    public const TRANSACTION_TYPE = [
        self::TRANSACTION_TYPE_INTERNAL_DEPOSIT,
        self::TRANSACTION_TYPE_INTERNAL_WITHDRAW,
        self::TRANSACTION_TYPE_DEPOSIT,
        self::TRANSACTION_TYPE_WITHDRAW,
        self::TRANSACTION_TYPE_BUY_NORMAL,
        self::TRANSACTION_TYPE_BUY_VIP,
    ];

    public const TRANSACTION_STATUS_PENDING = 0;
    public const TRANSACTION_STATUS_COMPLETED = 1;
    public const TRANSACTION_STATUS_FAILED = 2;
    public const TRANSACTION_STATUS_CANCELLED = 3;

    public const TRANSACTION_STATUS = [
        self::TRANSACTION_STATUS_PENDING,
        self::TRANSACTION_STATUS_COMPLETED,
        self::TRANSACTION_STATUS_FAILED,
        self::TRANSACTION_STATUS_CANCELLED,
    ];

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'tx',
        'username',
        'address',
        'note',
        'amount',
        'fee',
        'type',
        'status',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
