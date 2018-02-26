<?php

namespace App\Service;

use App\User;
use Exception;
use GuzzleHttp\Client;
use GuzzleHttp\Exception\RequestException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Kreait\Firebase\Factory;
use Kreait\Firebase\ServiceAccount;


class CheckTokenService
{
    private $request;

    /**
     * CheckTokenService constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
    }

    public function checkUserTokenInVk($token, User $user)
    {
        $result = json_decode(file_get_contents('https://api.vk.com/method/users.get?&access_token=' . $token), true);

        if (!isset($result['response'])) {
            abort(400, 'Token not found');
        }

        if ((string)$result['response'][0]['uid'] !== $this->request->input('uid')) {
            abort(400, 'Uid do not match');
        }

        $user->setFullName($result['response'][0]['first_name'] . ' ' . $result['response'][0]['last_name']);
        $user->setToken($token);
        $user->setUid((string)$result['response'][0]['uid']);
        return $user;
    }

    public function checkUserTokenInFacebook($token, User $user)
    {
        $result = json_decode(@file_get_contents('https://graph.facebook.com/v2.10/me?access_token=' . $token), true);

        if (!$result) {
            abort(400, 'Token not found in facebook');
        }

        if ((string)$result['id'] !== $this->request->input('uid')) {
            abort(400, 'Uid do not match');
        }

        $user->setFullName($result['name']);
        $user->setToken($token);
        $user->setUid((string)$result['id']);
        return $user;
    }

    public function checkUserTokenInGoogle($token, User $user)
    {
        $client = new Client();
        try {
            $res = $client->request('GET', 'https://www.googleapis.com/plus/v1/people/me?access_token=' . $token);
            $result = json_decode($res->getBody());

            if ($result->id !== $this->request->input('uid')) {
                abort(400, 'Uid do not match');
            }

            $user->setFullName($result->displayName);
            $user->setToken($token);
            $user->setUid((string)$result->id);
            return $user;
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

    public function checkUserTokenInFirebase($token, User $user)
    {
        $serviceAccount = ServiceAccount::fromJsonFile('../CARDbag-f7e88a3c37f3.json');
        $firebase = (new Factory)
            ->withServiceAccount($serviceAccount)
            ->create();
        $firebaseAuth = $firebase->getAuth();
        try {
            $idToken = $firebaseAuth->verifyIdToken($token);
            if ($idToken->getClaim('user_id') !== $this->request->input('uid')) {
                abort(400, 'Uid do not match');
            }
            $user->setFullName($idToken->getClaim('phone_number'));
            $user->setToken($token);
            $user->setUid($idToken->getClaim('user_id'));
            return $user;

        } catch (Exception $e) {
            abort(400, 'Token not found in Firebase');
        }
    }
}