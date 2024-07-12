<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Coin extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'coins';

    public const STATUS_INACTIVE = 0;
    public const STATUS_ACTIVE = 1;

    /**
     * @var array
     */
    protected $fillable = [
        'name',
        'image',
        'status',
    ];
}
