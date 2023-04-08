<?php

namespace App\Traits;

use App\Observers\MediaObserver;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Support\Str;

trait MediaOperation
{
    public static function bootMediaOperation()
    {
        static::observe(new MediaObserver());
    }

    public function image(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                if ($value){
                    return asset($value);
                }
                //TODO::Add Default value
                return $this->getDefaultImage() ?? asset('assets/images/defaults/global.png');
            },
            set: fn($value) => $value?->isValid() ? upload_image($value, Str::plural(mb_strtolower(Str::snake(class_basename(static::class))))) : null
        );
    }
}
