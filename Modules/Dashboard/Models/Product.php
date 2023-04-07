<?php

namespace Modules\Dashboard\Models;

use App\Models\User;
use App\Traits\StatisticOperation;
use Astrotomic\Translatable\Contracts\Translatable as TranslatableContract;
use Astrotomic\Translatable\Translatable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Database\Eloquent\Relations\HasOneThrough;

class Product extends Model implements TranslatableContract
{
    use HasFactory, Translatable, StatisticOperation;
    protected $guarded = ['id','created_at','updated_at','deleted_at'];
    public $translatedAttributes = ['name', 'description'];

    public function scopeByUser(Builder $q, ?User $user = null): Builder
    {
        if ($user?->user_type === User::VENDOR){
            return $q->whereRelation('center','centers.user_id', $user->id);
        }elseif (! $user || $user?->user_type === User::CUSTOMER){
            return $q->where('products.is_active', true);
        }else {
            return $q;
        }
    }

    public function center(): BelongsTo
    {
        return $this->belongsTo(Center::class);
    }

    public function user(): HasOneThrough
    {
        return $this->hasOneThrough(User::class, Center::class, 'id', 'id', 'center_id', 'user_id');
    }

    public function category():BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function attachments(): HasMany
    {
        return $this->hasMany(ProductMedia::class);
    }
}
