<?php

namespace App\Service;


interface CheckTokenInterface
{
    /**
     * Метод проверки токена пользователя в социальной сети
     * @param $token
     * @param $uid
     * @return bool|\UserProfile
     */
    public function checkUserTokenInSocialNetwork($token, $uid);
}