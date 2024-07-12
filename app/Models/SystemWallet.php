<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SystemWallet extends Model
{
    use HasFactory;

    protected $table = 'system_wallets';

    /**
     * @var string[]
     */
    protected $fillable = ['value'];
}
