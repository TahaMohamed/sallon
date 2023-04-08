<?php

namespace Modules\Vendor\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Modules\Dashboard\Models\Center;
use Modules\Dashboard\Models\Department;
use Modules\Dashboard\Models\Seat;

class Employee extends Model
{
    use HasFactory;
    protected $guarded = ['id', 'updated_at', 'created_at'];

    public function user():BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function center():BelongsTo
    {
        return $this->belongsTo(Center::class);
    }

    public function department():BelongsTo
    {
        return $this->belongsTo(Department::class);
    }

    public function seat():BelongsTo
    {
        return $this->belongsTo(Seat::class);
    }
}
