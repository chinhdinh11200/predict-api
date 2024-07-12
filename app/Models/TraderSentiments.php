<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TraderSentiments extends Model
{
    protected $fillable = ['sell', 'buy'];
    use HasFactory;
}
