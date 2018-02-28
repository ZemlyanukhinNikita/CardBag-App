<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use UserProfile;

class CheckUserTokenGoogleService implements CheckTokenInterface
{
    private $client;

    /**
     * CheckUserTokenGoogleService constructor.
     * @param Client $client
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
        try {
            $res = $this->client->request('GET',
                'https://www.googleapis.com/oauth2/v3/userinfo?access_token=' . $token);
            $result = json_decode($res->getBody());
            if ($result->sub !== $uid) {
                return false;
            }
            return new UserProfile($result->name, $token, $result->sub);

        } catch (RequestException $e) {
            Log::error('Exception' . $e->getMessage() . ' ' . $e->getCode());
            return false;
        }
    }
}