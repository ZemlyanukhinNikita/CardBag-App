<?php

namespace App\Service;

use GuzzleHttp\Client;
use UserProfile;

class VkService implements SocialNetworkInterface
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
     * @param $token
     * @param $uid
     * @return bool|UserProfile
     */
    public function checkUserTokenInSocialNetwork($token, $uid)
    {
        $res = $this->client->request('GET',
            'https://api.vk.com/method/users.get?v=5.52&access_token=' . $token);
        $result = json_decode($res->getBody());

        if (!isset($result->response)) {
            return false;
        }

        if ((string)$result->response[0]->id !== $uid) {
            return false;
        }

        return new UserProfile($result->response[0]->first_name . ' ' .
            $result->response[0]->last_name, $token, (string)$result->response[0]->id);
    }
}
