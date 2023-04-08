<?php

namespace App\Contracts;

use App\Models\User;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

interface RepositoryInterface
{
    public function allPaginate(?int $perPage = null): LengthAwarePaginator;

    public function create(array $data): void;

    public function update(array $data, int $id): void;

    public function delete(int $id): bool;

    public function find(int $id, ?bool $isShow = null): ?Model;

    public function setModel(): void;

}
