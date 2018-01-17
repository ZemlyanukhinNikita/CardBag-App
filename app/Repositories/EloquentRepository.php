<?php

namespace app\Repositories;


use Illuminate\Database\Eloquent\Model;

abstract class EloquentRepository implements ModelInterface
{

    public abstract function getModel();

    public function create(array $values): Model
    {
        return $this->getModel()->create($values);
    }

    public function findAllBy(string $field, $value)
    {
        return $this->getModel()->all()->where($field, $value);
    }

    public function findOneBy(string $field, $value)
    {
        return $this->getModel()->select($field)->where($field, $value)->first();
    }

    public function findOneByTwoArguments(string $field, $value, $arg)
    {
        return $this->getModel()->select($field)->where($value, $arg)->first();
    }

    public function findAll()
    {
        return $this->getModel()->all();
    }
}