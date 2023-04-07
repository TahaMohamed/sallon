<?php

namespace Modules\Dashboard\Models;

use App\Traits\MediaOperation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductMedia extends Model
{
    use MediaOperation;
    protected $guarded = ['id','created_at','updated_at','deleted_at'];

    public function category():BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    protected function getDefaultImage(): string
    {
        return asset('assets/images/defaults/product.png');
    }
}
