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

    public function checkUserTokenInSocialNetwork($token, $uid)
    {
        try {
            $res = $this->client->request('GET', 'https://www.googleapis.com/oauth2/v3/userinfo?access_token=' . $token);
            $result = json_decode($res->getBody());

            if ($result->sub !== $uid) {
                abort(400, 'Uid do not match');
            }
            return new UserProfile($result->name, $token, $result->sub);

        } catch (RequestException $e) {
            if ($e->getCode() === 403) {
                abort(400, 'Token not found in Google');
            }
            if ($e->getCode() === 401) {
                abort(400, 'Uid do not match');
            }
            Log::error('Exception' . $e->getMessage() . ' ' . $e->getCode());
        }
    }
}