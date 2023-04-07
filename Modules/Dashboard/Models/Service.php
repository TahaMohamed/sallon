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

    public function centers(): BelongsToMany
    {
        return $this->belongsToMany(Center::class);
    }

    protected function getDefaultImage(): string
    {
        return asset('assets/images/defaults/service.png');
    }
}
