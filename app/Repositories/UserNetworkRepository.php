<?php


namespace App\Repositories;


use App\UserNetwork;

class UserNetworkRepository extends EloquentRepository implements UserNetworkInterface
{

    /**
     * {@inheritDoc}
     * Абстрактный метод получения модели, реализуется в дочерних классах
     * @return mixed
     */
    public function getModel()
    {
        return new UserNetwork();
    }
}