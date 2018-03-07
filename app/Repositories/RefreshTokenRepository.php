<?php


namespace app\Repositories;


use app\RefreshToken;

class RefreshTokenRepository extends EloquentRepository implements RefreshTokenInterface
{

    /**
     * {@inheritDoc}
     * Абстрактный метод получения модели, реализуется в дочерних классах
     * @return mixed
     */
    public function getModel()
    {
        return new RefreshToken();
    }
}