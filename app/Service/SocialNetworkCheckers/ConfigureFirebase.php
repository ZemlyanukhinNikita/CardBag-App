<?php


namespace app\Service\SocialNetworkCheckers;


use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;

class ConfigureFirebase
{
    private $firebase;
    private $serviceAccount;

    /**
     * ConfigureFirebase constructor.
     * @param Factory $firebase
     * @param ServiceAccount $serviceAccount
     */
    public function __construct(Factory $firebase, ServiceAccount $serviceAccount)
    {
        $this->firebase = $firebase;
        $this->serviceAccount = $serviceAccount;
    }

    public function getFirebaseConfigure()
    {
        return $this->firebase->withServiceAccount($this->serviceAccount->fromJsonFile('../CARDbag-d01707728926.json'))->create();
    }
}