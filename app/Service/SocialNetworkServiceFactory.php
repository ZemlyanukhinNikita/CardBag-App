<?php

namespace App\Service;


use app\Service\SocialNetworkCheckers\ConfigureFirebase;
use GuzzleHttp\Client;

class SocialNetworkServiceFactory
{
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
            case 'Vk':
                return new CheckUserTokenVkService($this->client);
            case 'Facebook':
                return new CheckUserTokenFacebookService($this->client);
            case 'Google':
                return new CheckUserTokenGoogleService($this->client);
            case 'Firebase':
                return new CheckUserTokenFirebaseService($this->configureFirebase);
        }
        abort(400, 'No implemented');
    }
}