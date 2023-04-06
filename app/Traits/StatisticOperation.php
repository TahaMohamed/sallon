<?php

namespace App\Traits;

use App\Observers\StatisticObserver;

trait StatisticOperation
{
    public static function bootStatisticOperation()
    {
        static::observe(new StatisticObserver());
    }
}
