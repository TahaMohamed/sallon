<?php

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Repositories\Actions\Operation;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Dashboard\Models\Product;

class ProductRepository extends Operation implements RepositoryInterface
{
    public function __construct()
    {
        $this->setModel();
    }

    public function allPaginate(?int $perPage = null): LengthAwarePaginator
    {
        return $this->getQuery()
            ->latest('id')
            ->paginate((int)($perPage ?? config("globals.pagination.per_page")));
    }

    public function create(array $data): void
    {
        $this->store(new Product, $data);
    }

    public function update(array $data, int $id): void
    {
        $product = $this->find($id);
        $this->store($product, $data);
        if ($data['deleted_attachments']){
            $product->attachments()->whereIn('product_media.id',$data['deleted_attachments'])->delete();
        }
    }

    public function delete(int $id): bool
    {
        return $this->find($id)->delete();
    }

    public function find(int $id, ?bool $isShow = null): ?Product
    {
        return $this->getQuery()
            ->when(! is_null($isShow), function ($q) use ($isShow){
                $q->when(!$isShow, fn($q) => $q->with('translations'))
                    ->when($isShow, fn($q) => $q->with('translation'));
            })
            ->findOrFail($id);
    }

    private function store(Product $product, array $data)
    {
        $product->fill($data)->save();
        if ($data['attachments']){
            $product->attachments()->createMany($data['attachments']);
        }
    }

    public function setModel(): void
    {
        $this->model = Product::class;
    }
}
