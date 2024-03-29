<?php

namespace Modules\Dashboard\Models;

use App\Traits\MediaOperation;
use App\Traits\StatisticOperation;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Service extends Model implements TranslatableContract
{
    use HasFactory, Translatable, StatisticOperation, MediaOperation;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    public $translatedAttributes = ['name', 'description'];

    public function scopeActive(Builder $query): Builder
    {
        return $query->where('is_active', true);
    }

    public function centers(): BelongsToMany
    {
        return $this->belongsToMany(Center::class)->withPivot(['price','is_available','is_soon']);
    }

    protected function getDefaultImage(): string
    {
        return asset('assets/images/defaults/service.png');
    }
}
