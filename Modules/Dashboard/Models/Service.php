<?php

namespace Modules\Dashboard\Models;

use App\Traits\MediaOperation;
use App\Traits\StatisticOperation;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model implements TranslatableContract
{
    use HasFactory, Translatable, StatisticOperation, MediaOperation;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    public $translatedAttributes = ['name', 'description'];

    public function image(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value){
                    return asset($value);
                }
                //TODO::Add Default value
                return asset('assets/images/defaults/service.png');
            },
            set: fn($value) => upload_image($value, 'users')
        );
    }
    public function centers(): BelongsToMany
    {
        return $this->belongsToMany(Center::class);
    }
}
