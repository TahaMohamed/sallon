<?php

namespace Modules\Dashboard\Models;

use App\Models\User;
use App\Traits\MediaOperation;
use App\Traits\StatisticOperation;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasManyThrough;
use Modules\Vendor\Models\Employee;

class Seat extends Model implements TranslatableContract
{
    use HasFactory, Translatable, StatisticOperation, MediaOperation;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    public $translatedAttributes = ['name', 'description'];

    public function center(): BelongsTo
    {
        return $this->belongsTo(Center::class);
    }

    public function employees(): HasMany
    {
        return $this->hasMany(Employee::class);
    }

    public function users(): HasManyThrough
    {
        return $this->hasManyThrough(User::class,Employee::class, 'seatid', 'id', 'id', 'user_id');
    }
}
