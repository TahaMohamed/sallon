<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use App\Traits\MediaOperation;
use App\Traits\StatisticOperation;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Illuminate\Database\Eloquent\Relations\HasOne;
use Illuminate\Database\Query\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;
use Modules\Dashboard\Models\Center;
use Modules\Dashboard\Models\Department;
use Modules\Dashboard\Models\Role;
use Modules\Dashboard\Models\Seat;
use Modules\Vendor\Models\Attendance;
use Modules\Vendor\Models\Employee;

class User extends Authenticatable
{
    use HasApiTokens, HasFactory, Notifiable, StatisticOperation, MediaOperation;
    public const SUPERADMIN = 'superadmin';
    public const ADMIN = 'admin';
    public const CUSTOMER = 'customer';
    public const VENDOR = 'vendor';
    public const EMPLOYEE = 'employee';
    public const TYPES = [self::SUPERADMIN, self::ADMIN, self::CUSTOMER, self::VENDOR, self::EMPLOYEE];
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

    public static function getIntialPassword($length = 8): string
    {
        return '12345678'; //random_code(length: $length, letters: false,symbols: false);
    }

    public function scopeIsBanned($q): Builder
    {
        return $q->where('unbanned_at','>=',now()->endOfDay());
    }

    public function isBanned(): bool
    {
        return (bool)$this->unbanned_at?->gte(now());
    }

    public function getDefaultImage(): string
    {
        return asset('assets/images/defaults/avatar.png');
    }

    public function hasPermission($routeName): bool
    {
        return match ($this->user_type){
            self::SUPERADMIN => true,
            self::ADMIN => $this->roles()
                ->whereHas('permissions', fn($q) => $q->where('permissions.route', $routeName))
                ->exists(),
            default => false
        };
    }

    public function center(): HasOne
    {
        return $this->hasOne(Center::class);
    }

    public function employeeCenter(): HasManyThrough
    {
        return $this->hasOneThrough(Center::class, Employee::class, 'user_id', 'id', 'id', 'center_id');
    }

    public function employeeSeat(): HasManyThrough
    {
        return $this->hasOneThrough(Seat::class, Employee::class, 'user_id', 'id', 'id', 'seat_id');
    }

    public function employeeDepartment(): HasManyThrough
    {
        return $this->hasOneThrough(Department::class, Employee::class, 'user_id', 'id', 'id', 'department_id');
    }

    public function employee():HasOne
    {
        return $this->hasOne(Employee::class);
    }

    public function roles():BelongsToMany
    {
        return $this->belongsToMany(Role::class);
    }

    public function attendances(): HasMany
    {
        return $this->hasMany(Attendance::class, 'employee_id');
    }
}
