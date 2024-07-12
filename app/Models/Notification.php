<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Notification extends Model
{
    use HasFactory, SoftDeletes;

    public const STATUS_UNREAD = 0;
    public const STATUS_READ = 1;

    protected $table = 'notifications';

    protected $fillable = [
        'title',
        'message',
        'variables',
        'is_read',
    ];
}
