<?php

namespace app\Repositories;


use Illuminate\Database\Eloquent\Model;

/**
 *
 * {@inheritdoc}
 */
abstract class EloquentRepository implements ModelInterface
{
    /**
     * {@inheritDoc}
     * Абстрактный метод получения модели, реализуется в дочерних классах
     * @return mixed
     */
    public abstract function getModel();

    public function create(array $values): Model
    {
        return $this->getModel()->create($values);
    }

    public function findAllBy(string $field, $value)
    {
        return $this->getModel()->where($field, $value)->get();
    }

    public function findOneBy(string $field, $value)
    {
        return $this->getModel()->where($field, $value)->first();
    }

    public function findAll()
    {
        return $this->getModel()->all();
    }

    public function findAllOrderBy($field)
    {
        return $this->getModel()->orderBy($field)->get();
    }
}