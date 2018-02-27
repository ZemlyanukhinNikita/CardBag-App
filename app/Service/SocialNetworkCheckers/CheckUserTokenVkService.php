<?php

namespace App\Service;

use GuzzleHttp\Client;
use UserProfile;

class CheckUserTokenVkService implements CheckTokenInterface
{
    private $client;

    /**
     * CheckUserTokenVkService constructor.
     * @param $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * CheckTokenService constructor.
     * @param $token
     * @param $uid
     * @return UserProfile
     */
    public function checkUserTokenInSocialNetwork($token, $uid)
    {
        $res = $this->client->request('GET',
            'https://api.vk.com/method/users.get?&access_token=' . $token);
        $result = json_decode($res->getBody());
        if (!isset($result->response)) {
            abort(400, 'Token not found in Vk');
        }

        if ((string)$result->response[0]->uid !== $uid) {
            abort(400, 'Uid do not match');
        }

        return new UserProfile($result->response[0]->first_name . ' ' .
            $result->response[0]->last_name, $token, (string)$result->response[0]->uid);
    }
}