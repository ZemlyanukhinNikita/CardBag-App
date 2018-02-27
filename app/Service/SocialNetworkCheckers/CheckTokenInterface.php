<?php

namespace App\Service;

interface CheckTokenInterface
{
    public function checkUserTokenInSocialNetwork($token, $uid);
}