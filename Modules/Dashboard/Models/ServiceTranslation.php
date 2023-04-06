<?php

namespace Modules\Dashboard\Models;

use Illuminate\Database\Eloquent\Model;

class ServiceTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name','description'];
}
