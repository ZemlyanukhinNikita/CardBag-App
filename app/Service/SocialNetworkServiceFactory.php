<?php

namespace App\Service;

class SocialNetworkServiceFactory
{
    public function getUserSocialToken($network)
    {
        switch ($network) {
            case 'Vk':
                return new CheckUserTokenVkService();
            case 'Facebook':
                return new CheckUserTokenFacebookService();
        }
        abort(400, 'No implemented');
    }
}