<?php

namespace app\Repositories;

<<<<<<< HEAD

=======
>>>>>>> develop
use App\User;

class UserRepository extends EloquentRepository implements UserInterface
{
<<<<<<< HEAD
=======
    /**
     * {@inheritDoc}
     * Метод возвращения модели User
     * @return User
     */
>>>>>>> develop
    public function getModel()
    {
        return new User();
    }
<<<<<<< HEAD

    /**
     * Метод получения всех карточек пользователя из базы данных
     * @param User $user
     * @return mixed
     */
=======
>>>>>>> develop
}