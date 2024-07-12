<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\SoftDeletes;

class WalletDepositUser extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'wallet_deposit_users';

    /**
     * @var array
     */
    protected $fillable = [
        'user_id',
        'address',
        'private_key',
        'public_key',
        'balance',
        'is_transfer_fee',
    ];

    /**
     * @return BelongsTo
     */
    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
