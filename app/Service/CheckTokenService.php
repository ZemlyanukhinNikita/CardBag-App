<?php

namespace App\Service;

use App\User;
use Illuminate\Http\Request;

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
}