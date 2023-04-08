<?php

namespace App\Repositories;

use App\Contracts\RepositoryInterface;
use App\Models\User;
use App\Repositories\Actions\Operation;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Pagination\LengthAwarePaginator;

class UserRepository extends Operation implements RepositoryInterface
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
        $this->store(new User, $data);
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
        return $this->getQuery()->findOrFail($id);
    }

    private function store(User $user, array $data)
    {
        if (!$user->getKey()){
            $data['password'] = User::getIntialPassword();
        }

        if (is_bool(@$data['is_phone_verified']) &&  $data['is_phone_verified'] && !$user?->phone_verified_at){
            $data['phone_verified_at'] = now();
        }

        if (is_bool(@$data['is_email_verified']) &&  $data['is_email_verified'] && !$user?->email_verified_at){
            $data['email_verified_at'] = now();
        }

        $user->fill($data)->save();
//        TODO::After make roles
//        if ($user->user_type === User::ADMIN){
//            $user->roles()->sync($data['roles']);
//        }
        match ($user->user_type){
          User::EMPLOYEE => $this->setEmployeeData($user, $data),
          default => null
        };

    }

    private function setEmployeeData($user, $data)
    {
        $user->employee()->updateOrCreate(['center_id' => $data['center_id']], array_only($data,['center_id','department_id','seat_id','salary']));
    }

    public function setModel(): void
    {
        $this->model = User::class;
    }
}
