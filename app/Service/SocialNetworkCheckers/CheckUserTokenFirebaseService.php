<?php

namespace App\Service;


use Exception;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;
use UserProfile;

class CheckUserTokenFirebaseService implements CheckTokenInterface
{

    public function checkUserTokenInSocialNetwork($token, $uid)
    {
        $serviceAccount = ServiceAccount::fromJsonFile('../CARDbag-f7e88a3c37f3.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
        $firebaseAuth = $firebase->getAuth();
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