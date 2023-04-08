<?php

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Models\User;
use App\Repositories\Actions\Operation;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository extends Operation implements RepositoryInterface
{
    protected User $user;

    public function queryBuilder(): Builder
    {
        return User::query()
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
        $this->store($this->user, $data);
    }

    public function update(array $data, int $id): void
    {
        $user = $this->find($id);
        $this->store($user, $data);
    }

    public function delete(int $id): bool
    {
        return $this->find($id)->delete();
    }

    public function find(int $id, ?bool $isShow = null): ?Model
    {
        return $this->queryBuilder()->findOrFail($id);
    }

    private function store(User $user, array $data)
    {
        $user->fill($data)->save();
//        TODO::After make roles
//        if ($user->user_type === User::ADMIN){
//            $this->user->roles()->sync($data['roles']);
//        }
    }
}
