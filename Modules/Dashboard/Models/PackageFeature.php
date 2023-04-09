<?php

namespace Modules\Dashboard\Models;

use App\Traits\StatisticOperation;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class PackageFeature extends Model implements TranslatableContract
{
    use HasFactory, Translatable, StatisticOperation;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    public $translatedAttributes = ['name', 'description'];

    protected static function newFactory()
    {
        return \Modules\Dashboard\Database\factories\PackageFeatureFactory::new();
    }

    public function packages(): BelongsToMany
    {
        return $this->belongsToMany(Package::class);
    }
}
