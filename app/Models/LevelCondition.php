<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class LevelCondition extends Model
{
    use HasFactory;

    protected $table = 'level_conditions';

    /**
     * @var array
     */
    protected $fillable = [
        'level',
        'condition_f1',
        'volume_week',
    ];
}
