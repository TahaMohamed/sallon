<?php

namespace Modules\Dashboard\Models;

use Illuminate\Database\Eloquent\Model;

class PackageTranslation extends Model
{
    public $timestamps = false;
    protected $fillable = ['name','description', 'short_description'];
}
