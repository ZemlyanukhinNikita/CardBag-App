<?php

namespace App\Service;

use App\User;

interface SocialNetworkInterface
{
    /**
     * Метод обновления токена пользователя
     * если у пользователя прошло действие токена
     * @param User $result
     * @return mixed
     */
    public function refreshUserToken(User $result);

    /**
     * Метод регистрации нового пользователя
     * @param User $result
     * @return mixed
     */
    public function registerNewUser(User $result);

    /**
     * Метод авторизация пользователя
     * @param $token
     * @return mixed
     */
    public function auth($token);
}