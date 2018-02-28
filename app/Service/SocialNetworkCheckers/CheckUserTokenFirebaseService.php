<?php

namespace App\Service;


use Exception;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase;
use Kreait\Firebase\ServiceAccount;
use UserProfile;

class CheckUserTokenFirebaseService implements CheckTokenInterface
{
    private $firebase;

    /**
     * CheckUserTokenFirebaseService constructor.
     * @param Firebase $firebase
     */
    public function __construct(Firebase $firebase)
    {
        $this->firebase = $firebase;
    }

    public function checkUserTokenInSocialNetwork($token, $uid)
    {
        $firebaseAuth = $this->firebase->getAuth();
        try {
            $idToken = $firebaseAuth->verifyIdToken($token);
        } catch (Exception $e) {
            Log::error('Exception' . $e->getMessage() . ' ' . $e->getCode());
            return false;
        }

        if ($idToken->getClaim('user_id') !== $uid) {
            return false;
        }
        return new UserProfile($idToken->getClaim('phone_number'), $token, $idToken->getClaim('user_id'));
    }
}