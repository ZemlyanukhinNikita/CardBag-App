<?php

namespace App\Service;

interface SocialNetworkInterface
{
    public function refreshUserToken($user);

    public function registerNewUser($user);

    public function checkUserToken($token);
}