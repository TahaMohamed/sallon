<?php

namespace Modules\Dashboard\Models;

use App\Traits\StatisticOperation;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;

class Package extends Model implements TranslatableContract
{
    use HasFactory, Translatable, StatisticOperation;
    protected $guarded = ['id', 'created_at', 'updated_at'];
    public $translatedAttributes = ['name', 'description'];

    protected $casts = [
        'duration' => \App\Enums\Package::class
    ];

    public function features(): BelongsToMany
    {
        return $this->belongsToMany(PackageFeature::class);
    }
}
