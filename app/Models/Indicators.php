<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Indicators extends Model
{
    protected $fillable = ['sell', 'buy', 'neutral', 'key'];
    use HasFactory;
}
