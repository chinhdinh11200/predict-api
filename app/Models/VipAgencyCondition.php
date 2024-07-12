<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class VipAgencyCondition extends Model
{
    use HasFactory;

    protected $table = 'vip_agency_conditions';

    /**
     * @var array
     */
    protected $fillable = [
        'level',
        'generation',
        'amount',
    ];
}
