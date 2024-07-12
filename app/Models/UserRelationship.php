<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Kalnoy\Nestedset\NodeTrait;

class UserRelationship extends Model
{
    use HasFactory, NodeTrait;

    protected $table = 'user_relationships';

    /**
     * @var string[]
     */
    protected $fillable = [
        'user_id',
        '_lft',
        '_rgt',
        'parent_id',
    ];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class, 'user_id');        
    }
}
