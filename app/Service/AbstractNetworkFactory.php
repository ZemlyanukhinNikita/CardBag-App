<?php

namespace App\Service;

use Exception;

abstract class AbstractNetworkFactory
{
    public static function getSocialNetwork($request, $userRepository, $tokenRepository, $networkRepository, $network)
    {
        switch ($network) {
            case 1:
                return new VkAuthorizeService($request, $userRepository, $tokenRepository, $networkRepository);
            case 2:
                return new FacebookAuthorizeService($request, $userRepository, $tokenRepository, $networkRepository);
        }
        throw new Exception('Bad request');
    }

    abstract public function auth();
}