<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class NormalAgencyCondition extends Model
{
    use HasFactory;

    protected $table = 'normal_agency_conditions';

    /**
     * @var array
     */
    protected $fillable = [
        'level',
        'generation',
        'amount',
    ];
}
