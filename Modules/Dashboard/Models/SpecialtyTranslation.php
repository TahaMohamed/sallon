<?php

namespace Modules\Dashboard\Models;

use Illuminate\Database\Eloquent\Model;

class SpecialtyTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name','description'];
}
