<?php

namespace App\Repositories;

use App\Models\User;
use App\Contracts\RepositoryInterface;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Dashboard\Models\Product;

class ProductRepository implements RepositoryInterface
{
    protected mixed $with = [];
    protected Product $product;

    public function create(array $data, User $user): void
    {
        $this->product->fill($data + ['added_by_id' => $user->id])->save();
        if ($data['attachments']){
            $this->product->attachments()->createMany($data['attachments']);
        }
    }

    public function update(array $data, int $id, User $forUser): bool
    {
        $product = $this->find($id, $forUser);
        $product->fill($data + ['added_by_id' => auth()->id()])->save();
        if ($data['attachments']){
            $product->attachments()->createMany($data['attachments']);
        }
        if ($data['deleted_attachments']){
            $product->attachments()->whereIn('product_media.id',$data['deleted_attachments'])->delete();
        }
        return $product->update($data);
    }

    public function delete(int $id, User $byUser): bool
    {
        return $this->find($id, $byUser)->delete();
    }

    public function find(int $id, ?User $byUser = null, ?bool $isShow = null): ?Product
    {
        return Product::query()
            ->with($this->with)
            ->byUser($byUser)
            ->when(! is_null($isShow), function ($q) use ($isShow){
                $q->when(!$isShow, fn($q) => $q->with('translations'))
                    ->when($isShow, fn($q) => $q->with('translation'));
            })
            ->findOrFail($id);
    }

    public function allBuilder(?User $byUser = null): Builder
    {
        return Product::query()
            ->byUser($byUser)
            ->with($this->with)
            ->latest('id');
    }

    public function allPaginate(?User $byUser = null, ?int $perPage = null): LengthAwarePaginator
    {
        return $this->allBuilder($byUser)
            ->paginate((int)($perPage ?? config("globals.pagination.per_page")));
    }

    public function with(mixed $with = [])
    {
        $this->with = $with;
        return $this;
    }
}
