<?php

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Repositories\Actions\Operation;
use Illuminate\Pagination\LengthAwarePaginator;
use Illuminate\Support\Arr;
use Modules\Dashboard\Models\Center;

class CenterRepository extends Operation implements RepositoryInterface
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
        $this->store(new Center, $data);
    }

    public function update(array $data, int $id): void
    {
        $center = $this->find($id);
        $this->store($center, $data);
    }

    public function delete(int $id): bool
    {
        return $this->find($id)->delete();
    }

    public function find(int $id, ?bool $isShow = null): ?Center
    {
        return $this->getQuery()
            ->when(! is_null($isShow), function ($q) use ($isShow){
                $q->when(!$isShow, fn($q) => $q->with('translations'))
                    ->when($isShow, fn($q) => $q->with('translation'));
            })
            ->findOrFail($id);
    }

    private function store(Center $center, array $data)
    {
        $center->fill($data)->save();
        if ($data['services']){
            $center->services()->sync(Arr::keyBy($data['services'], 'service_id'));
        }
    }

    public function setModel(): void
    {
        $this->model = Center::class;
    }
}
