<?php

namespace App\Service;

interface SocialNetworkInterface
{
    public function refreshUserToken($user, $token);

    public function registerNewUser();

    public function checkUserToken($token);
}