<?php

namespace App\Service;


class SocialNetworkFactory
{
    public function getSocialNetwork($network)
    {
        switch ($network) {
            case 'Vk':
                return app(VkService::class);
            case 'Facebook':
                return app(FacebookService::class);
            case 'Google':
                return app(GoogleService::class);
            case 'Firebase':
                return app(FirebaseService::class);
        }
        abort(400, 'No implemented');
    }
}