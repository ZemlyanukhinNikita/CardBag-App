<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use UserProfile;

class CheckUserTokenFacebookService implements CheckTokenInterface
{
    private $client;

    /**
     * CheckUserTokenFacebookService constructor.
     * @param $client
     */
    public function __construct(Client $client)
    {
        $this->client = $client;
    }

    /**
     * @param $token
     * @param $uid
     * @return UserProfile
     */
    public function checkUserTokenInSocialNetwork($token, $uid)
    {
        try {
            $res = $this->client->request('GET', 'https://graph.facebook.com/v2.10/me?access_token=' . $token);
            $result = json_decode($res->getBody());
            if ($result->id !== $uid) {
                abort(400, 'Uid do not match');
            }
            return new UserProfile($result->name, $token, $result->id);

        } catch (RequestException $e) {
            if ($e->getCode() === 400) {
                abort(400, 'Token not found in Facebook');
            }
            if ($e->getCode() === 401) {
                abort(400, 'Uid do not match');
            }
            Log::error('Exception' . $e->getMessage() . ' ' . $e->getCode());
        }
    }
}
