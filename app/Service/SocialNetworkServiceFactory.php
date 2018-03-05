<?php

namespace App\Service;

use app\Service\SocialNetworkCheckers\ConfigureFirebase;
use GuzzleHttp\Client;

class SocialNetworkServiceFactory
{
    const VK_TITLE = 'Vk';
    const FACEBOOK_TITLE = 'Facebook';
    const GOOGLE_TITLE = 'Google';
    const FIREBASE_TITLE = 'Firebase';

    private $client;
    private $configureFirebase;

    /**
     * SocialNetworkServiceFactory constructor.
     * @param Client $client
     * @param ConfigureFirebase $configureFirebase
     * @internal param $comfigureFirebase
     */
    public function __construct(Client $client, ConfigureFirebase $configureFirebase)
    {
        $this->client = $client;
        $this->configureFirebase = $configureFirebase;
    }


    public function getUserSocialToken($network)
    {
        switch ($network) {
            case self::VK_TITLE:
                return new CheckUserTokenVkService();
            case self::FACEBOOK_TITLE:
                return new CheckUserTokenFacebookService();
            case self::GOOGLE_TITLE:
                return new CheckUserTokenGoogleService($this->client);
            case self::FIREBASE_TITLE:
                return new CheckUserTokenFirebaseService($this->configureFirebase);
        }
        abort(400, 'No implemented');
    }
}