<?php

namespace App\Service;

use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Support\Facades\Log;
use UserProfile;

class CheckUserTokenGoogleService implements CheckTokenInterface
{

    public function checkUserTokenInSocialNetwork($token, $uid)
    {
        $client = new Client();
        try {
            $res = $client->request('GET', 'https://www.googleapis.com/plus/v1/people/me?access_token=' . $token);
            $result = json_decode($res->getBody());

            if ($result->id !== $uid) {
                abort(400, 'Uid do not match');
            }
            return new UserProfile($result->displayName, $token, $result->id);

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