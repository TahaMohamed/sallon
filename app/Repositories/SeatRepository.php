<?php

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Repositories\Actions\Operation;
use Illuminate\Pagination\LengthAwarePaginator;
use Modules\Dashboard\Models\Seat;

class SeatRepository extends Operation implements RepositoryInterface
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
        $this->store(new Seat, $data);
    }

    public function update(array $data, int $id): void
    {
        $seat = $this->find($id);
        $this->store($seat, $data);
    }

    public function delete(int $id): bool
    {
        return $this->find($id)->delete();
    }

    public function find(int $id, ?bool $isShow = null): ?Seat
    {
        return $this->getQuery()
            ->when(! is_null($isShow), function ($q) use ($isShow){
                $q->when(!$isShow, fn($q) => $q->with('translations'))
                    ->when($isShow, fn($q) => $q->with('translation'));
            })
            ->findOrFail($id);
    }

    private function store(Seat $seat, array $data)
    {
        $seat->fill($data)->save();
    }

    public function setModel(): void
    {
        $this->model = Seat::class;
    }
}
