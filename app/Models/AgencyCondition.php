<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class AgencyCondition extends Model
{
    use HasFactory;

    protected $table = 'agency_conditions';

    /**
     * @var array
     */
    protected $fillable = [
        'level',
        'generation',
        'percent',
    ];
}
