<?php

namespace App\Traits;

use App\Observers\MediaObserver;

trait MediaOperation
{
    public static function bootMediaOperation()
    {
        static::observe(new MediaObserver());
    }
}
