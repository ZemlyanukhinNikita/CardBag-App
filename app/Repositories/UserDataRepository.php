<?php


namespace App\Repositories;


use App\UserData;

class UserDataRepository extends EloquentRepository implements UserDataInterface
{

    /**
     * {@inheritDoc}
     * Абстрактный метод получения модели, реализуется в дочерних классах
     * @return mixed
     */
    public function getModel()
    {
        return new UserData();
    }
}