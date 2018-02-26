<?php

namespace App\Service;

use UserProfile;

class CheckUserTokenVkService implements CheckTokenInterface
{
    /**
     * CheckTokenService constructor.
     * @param $token
     * @param $uid
     * @return UserProfile
     */
    public function checkUserTokenInSocialNetwork($token, $uid)
    {
        $result = json_decode(file_get_contents('https://api.vk.com/method/users.get?&access_token=' . $token), true);

        if (!isset($result['response'])) {
            abort(400, 'Token not found');
        }

        if ((string)$result['response'][0]['uid'] !== $uid) {
            abort(400, 'Uid do not match');
        }

        return new UserProfile($result['response'][0]['first_name'] . ' ' .
            $result['response'][0]['last_name'], $token, (string)$result['response'][0]['uid']);
    }
}