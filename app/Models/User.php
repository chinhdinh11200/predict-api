<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, SoftDeletes;

    public const STATUS_ACTIVE = 1;
    public const STATUS_INACTIVE = 0;
    public const STATUS_LOCK = 2;

    public const AGENCY_STATUS_NON = 0;
    public const AGENCY_STATUS_REGULAR = 1;
    public const AGENCY_STATUS_VIP = 2;

    protected $table = 'users';

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'username',
        'fullname',
        'avatar',
        'email',
        'password',
        'refcode',
        'email_verified_at',
        'real_balance',
        'virtual_balance',
        'level',
        'agency_status',
        'status',
        'remember_token',
        'usdt_balance'
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var array<int, string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * @return HasOne
     */
    public function userRelationship(): HasOne
    {
        return $this->hasOne(UserRelationship::class, 'user_id');
    }

    public function setting(): HasOne
    {
        return $this->hasOne(UserSetting::class, 'user_id');
    }

    public function bets(): HasMany
    {
        return $this->hasMany(Bet::class, 'user_id', 'id');
    }
}
