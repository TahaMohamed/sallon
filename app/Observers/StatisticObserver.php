<?php

namespace App\Observers;

use App\Enums\UserType;
use App\Models\Statistic;
use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class StatisticObserver
{
    public function creating(Model $model)
    {
        self::incrementCount(mb_strtolower(Str::snake(class_basename($model::class))) . '_count');
    }


    public function deleted(Model $model)
    {
        self::decrementCount(mb_strtolower(Str::snake(class_basename($model::class))) . '_count', $model->created_at->format('Y-m-d'));
    }


    public static function incrementCount($key): void
    {
        $statistic = Statistic::firstOrCreate(['key' => $key, 'added_at' => date('Y-m-d')], ['value' => 0]);
        $statistic->increment('value');
    }

    public static function decrementCount($key, $added_at): void
    {
        Statistic::where(['key'=> $key, 'added_at' => $added_at])->decrement('value');
    }
}
