<?php

namespace Modules\Dashboard\Models;

use App\Traits\StatisticOperation;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Country extends Model implements TranslatableContract
{
    use HasFactory, Translatable, StatisticOperation;
    protected $guarded = ['id','created_at','updated_at','deleted_at'];
    public $translatedAttributes = ['name', 'description'];

    public function capital(): BelongsTo
    {
        return $this->belongsTo(City::class, 'capital_city_id');
    }

    public function cities(): HasMany
    {
        return $this->hasMany(City::class);
    }
}
