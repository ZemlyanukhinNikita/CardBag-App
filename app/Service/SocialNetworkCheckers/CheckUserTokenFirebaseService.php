<?php

namespace App\Service;


use app\Service\SocialNetworkCheckers\ConfigureFirebase;
use Exception;
use Kreait\Firebase\ServiceAccount;
use UserProfile;

class CheckUserTokenFirebaseService implements CheckTokenInterface
{
    private $firebase;

    /**
     * CheckUserTokenFirebaseService constructor.
     * @param $firebase
     */
    public function __construct(ConfigureFirebase $firebase)
    {
        $this->firebase = $firebase;
    }

    public function checkUserTokenInSocialNetwork($token, $uid)
    {
        $firebaseAuth = $this->firebase->getFirebaseConfigure()->getAuth();
        try {
            $idToken = $firebaseAuth->verifyIdToken($token);

        } catch (Exception $e) {
            abort(400, 'Token not found in Firebase');
        }

        if ($idToken->getClaim('user_id') !== $uid) {
            abort(400, 'Uid do not match');
        }
        return new UserProfile($idToken->getClaim('phone_number'), $token, $idToken->getClaim('user_id'));
    }
}