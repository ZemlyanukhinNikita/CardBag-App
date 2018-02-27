<?php

namespace app\Repositories;


use App\Token;

class TokenRepository extends EloquentRepository implements TokenInterface
{
    /**
     * {@inheritDoc}
     * Метод возвращения модели User
     */
    public function getModel()
    {
        return new Token();
    }
}