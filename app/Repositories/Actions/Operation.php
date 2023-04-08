<?php

namespace App\Repositories\Actions;

use Illuminate\Database\Eloquent\Model;
use Modules\Dashboard\Models\Center;

class Operation
{
    protected array $conditions = [];
    protected array $withRelations = [];
    protected array $countRelations = [];
    protected $model;

    public function getQuery()
    {
        return $this->model::query()
            ->when($this->getConditions(), fn($q) => $q->where($this->getConditions()))
            ->when($this->getWithRelation(), fn($q) => $q->with($this->getWithRelation()))
            ->when($this->getCountRelation(), fn($q) => $q->withCount($this->getCountRelation()));
    }

    public function with(array $relations = [])
    {
        $this->withRelations = $relations;
        return $this;
    }

    public function withCount(array $relations = [])
    {
        $this->countRelations = $relations;
        return $this;
    }

    public function where(array $conditions = [])
    {
        $this->conditions = $conditions;
        return $this;
    }

    protected function getConditions()
    {
        return $this->conditions;
    }

    protected function getWithRelation()
    {
        return $this->withRelations;
    }

    protected function getCountRelation()
    {
        return $this->countRelations;
    }
}
