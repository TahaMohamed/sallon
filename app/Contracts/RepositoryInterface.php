<?php

namespace App\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    public function create(array $data, User $byUser): void;

    public function update(array $data, int $id, User $forUser): bool;

    public function delete(int $id, User $byUser): bool;

    public function find(int $id, ?User $byUser = null, ?bool $isShow = null): ?Model;

    public function allBuilder(?User $byUser = null): Builder;

    public function allPaginate(?User $byUser = null, ?int $perPage = null): LengthAwarePaginator;
}
