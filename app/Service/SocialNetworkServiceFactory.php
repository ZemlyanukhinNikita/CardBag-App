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
            case 'Google':
                return new CheckUserTokenGoogleService();
            case 'Firebase':
                return new CheckUserTokenFirebaseService();
        }
        abort(400, 'No implemented');
    }
}