<?php

namespace app\Repositories;


use App\User;

interface UserInterface extends ModelInterface
{
    /**
     * Метод получения всех карточек пользователя из базы данных
     * @param User $user
     * @return mixed
     */
    public function getAllCards(User $user);
}