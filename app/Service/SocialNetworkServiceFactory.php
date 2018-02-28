<?php

namespace App\Service;


class SocialNetworkServiceFactory
{
    public function getUserSocialToken($network)
    {
        switch ($network) {
            case 'Vk':
                return app(CheckUserTokenVkService::class);
            case 'Facebook':
                return app(CheckUserTokenFacebookService::class);
            case 'Google':
                return app(CheckUserTokenGoogleService::class);
            case 'Firebase':
                return app(CheckUserTokenFirebaseService::class);
        }
        abort(400, 'No implemented');
    }
}