<?php

namespace App\Service;


interface SocialNetworkInterface
{
    /**
     * Метод проверки токена пользователя в социальной сети
     * @param $token
     * @param $uid
     * @return false|\UserProfile
     */
    public function checkUserTokenInSocialNetwork($token, $uid);
}
