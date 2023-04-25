<?php

namespace Modules\Vendor\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Attendance extends Model
{
    use HasFactory;

    protected $guarded = ['id','created_at','updated_at'];
    const PRESENT = 'present';
    const LEAVE = 'leave';
    const ABSENT = 'absent';
    const CASES = [self::PRESENT, self::ABSENT, self::LEAVE];
    protected $casts = [
        'date' => 'datetime'
    ];


    public function employee(): BelongsTo
    {
        return $this->belongsTo(User::class,'employee_id');
    }

    public function vendor(): BelongsTo
    {
        return $this->belongsTo(User::class,'vendor_id');
    }
}
