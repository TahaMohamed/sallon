<?php

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Repositories\Actions\Operation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Dashboard\Models\Service;

class ServiceRepository extends Operation implements RepositoryInterface
{
    protected Service $service;

    public function queryBuilder(): Builder
    {
        return Service::query()
            ->when($this->getConditions(), fn($q) => $this->getConditions())
            ->when($this->getWithRelation(), fn($q) => $this->with($this->getWithRelation()))
            ->when($this->getCountRelation(), fn($q) => $this->withCount($this->getCountRelation()));
    }

    public function allPaginate(?int $perPage = null): LengthAwarePaginator
    {
        return $this->queryBuilder()
            ->latest('id')
            ->paginate((int)($perPage ?? config("globals.pagination.per_page")));
    }

    public function create(array $data): void
    {
        $this->store($this->service, $data);
    }

    public function update(array $data, int $id): void
    {
        $service = $this->find($id);
        $this->store($service, $data);
    }

    public function delete(int $id): bool
    {
        return $this->find($id)->delete();
    }

    public function find(int $id, ?bool $isShow = null): ?Service
    {
        return $this->queryBuilder()
            ->when(! is_null($isShow), function ($q) use ($isShow){
                $q->when(!$isShow, fn($q) => $q->with('translations'))
                    ->when($isShow, fn($q) => $q->with('translation'));
            })
            ->findOrFail($id);
    }

    private function store(Service $service, array $data)
    {
        $service->fill($data)->save();
    }
}
