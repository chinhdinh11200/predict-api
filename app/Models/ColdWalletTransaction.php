<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ColdWalletTransaction extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'cold_wallet_transactions';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        'wallet_deposit_user_id',
        'hash',
        'value',
    ];
}
