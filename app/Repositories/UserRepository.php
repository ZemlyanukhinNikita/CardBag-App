<?php

namespace app\Repositories;


use App\User;

class UserRepository extends EloquentRepository implements UserInterface
{
    public function getModel()
    {
        return new User();
    }

    /**
     * Метод получения всех карточек пользователя из базы данных
     * @param User $user
     * @return mixed
     */
}