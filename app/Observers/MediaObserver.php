<?php

namespace App\Observers;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\File;

class MediaObserver
{
    public function deleted(Model $model)
    {
        if (file_exists(storage_path('app/public/'.$model->media))) {
            File::delete(storage_path('app/public/'.$model->media));
        }
    }
}
