<?php

namespace App\Service;

use UserProfile;

interface SocialNetworkInterface
{
    /**
     * Метод обновления токена пользователя
     * если у пользователя прошло действие токена
     * @param UserProfile $result
     * @return mixed
     */
    public function refreshUserToken(UserProfile $result);

    /**
     * Метод регистрации нового пользователя
     * @param UserProfile $result
     * @return mixed
     */
    public function registerNewUser(UserProfile $result);

    /**
     * Метод авторизация пользователя
     * @param $uid
     * @param $token
     * @param $network
     * @return mixed
     * @internal param $factory
     */
    public function auth($uid, $token, $network);
}