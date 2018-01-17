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
<<<<<<< HEAD
    }

    public function findOneByTwoArguments(string $field, $value, $arg)
    {
        return $this->getModel()->select($field)->where($value, $arg)->first();
=======
>>>>>>> develop
    }

    public function findAll()
    {
        return $this->getModel()->all();
    }
}