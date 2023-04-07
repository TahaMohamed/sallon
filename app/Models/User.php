<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\MediaOperation;
use App\Traits\StatisticOperation;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, StatisticOperation, MediaOperation;
    public const SUPERADMIN = 'superadmin';
    public const ADMIN = 'admin';
    public const CUSTOMER = 'customer';
    public const VENDOR = 'vendor';
    public const TYPES = [self::SUPERADMIN, self::ADMIN, self::CUSTOMER, self::VENDOR];
    protected $guarded = ['created_at','id','updated_at'];

    protected $hidden = ['password', 'remember_token'];

    protected $casts = [
        'email_verified_at' => 'datetime',
        'phone_verified_at' => 'datetime',
        'banned_at' => 'datetime',
        'unbanned_at' => 'datetime',
    ];

    public function password(): Attribute
    {
        return Attribute::make(
            set: fn($value) => bcrypt($value)
        );
    }

    public function scopeIsBanned($q): Builder
    {
        return $q->where('unbanned_at','>=',now()->endOfDay());
    }

    public function isBanned(): bool
    {
        return $this->unbanned_at?->gte(now());
    }

    public function getDefaultImage(): string
    {
        return asset('assets/images/defaults/avatar.png');
    }
}
