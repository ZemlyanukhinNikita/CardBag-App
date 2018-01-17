<?php

namespace app\Repositories;

use App\User;

class UserRepository extends EloquentRepository implements UserInterface
{
    /**
     * {@inheritDoc}
     * Метод возвращения модели User
     * @return User
     */
    public function getModel()
    {
        return new User();
    }
}