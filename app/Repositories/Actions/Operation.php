<?php

namespace App\Repositories\Actions;

class Operation
{
    protected array $conditions = [];
    protected array $withRelations = [];
    protected array $countRelations = [];

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
