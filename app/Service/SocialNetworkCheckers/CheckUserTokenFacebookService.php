<?php

namespace App\Service;

use UserProfile;

class CheckUserTokenFacebookService implements CheckTokenInterface
{
    /**
     * @param $token
     * @param $uid
     * @return UserProfile
     */
    public function checkUserTokenInSocialNetwork($token, $uid)
    {
        $result = json_decode(@file_get_contents('https://graph.facebook.com/v2.10/me?access_token=' . $token), true);

        if (!$result) {
            abort(400, 'Token not found in facebook');
        }

        if ((string)$result['id'] !== $uid) {
            abort(400, 'Uid do not match');
        }

        return new UserProfile($result['name'], $token, (string)$result['id']);
    }
}
