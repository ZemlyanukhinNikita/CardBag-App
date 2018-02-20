<?php

namespace App\Service;


use app\Repositories\UserInterface;
use Illuminate\Http\Request;

class FacebookAuthorizeService implements SocialNetworkInterface
{
    private $request;
    private $userRepository;

    /**
     * VkAuthorizeService constructor.
     * @param Request $request
     * @param UserInterface $userRepository
     */
    public function __construct(Request $request, UserInterface $userRepository)
    {
        $this->request = $request;
        $this->userRepository = $userRepository;
    }

    public function authFacebook()
    {
        $user = $this->userRepository->findOneBy('uid', $this->request->input('uid'));

        if ($user) {
            if ($user->uuid === $this->request->input('token')) {
                return response()->json($user);
            }
            return $this->refreshUserToken($user);
        }

        if (!$user) {
            return $this->registerNewUser($user);
        }
    }

    public function refreshUserToken($user)
    {
        if ($user->uuid !== $this->request->input('token')) {

            $result = $this->checkUserToken($this->request->input('token'));

            if ((string)$result['id'] === $this->request->input('uid')) {
                $this->userRepository->update('uid', $this->request->input('uid'), ['uuid' => $this->request->input('token')]);
                return $this->userRepository->findOneBy('uid', $this->request->input('uid'));
            }
            abort(400, 'Uid do not match');
        }
    }


    public function registerNewUser($user)
    {
        if (!$user) {

            $result = $this->checkUserToken($this->request->input('token'));

            if ($this->userRepository->findOneBy('uuid', $this->request->input('token'))
            ) {
                abort(400, 'Token must be unique');
            }

            if ((string)$result['id'] === $this->request->input('uid')) {
                $this->userRepository->create([
                    'uuid' => $this->request->input('token'),
                    'uid' => $this->request->input('uid'),
                    'full_name' => $result['name'],
                    'network_id' => $this->request->input('network'),
                ]);
                return $this->userRepository->findOneBy('uid', $this->request->input('uid'));
            }
            abort(400, 'Uid do not match');
        }
    }


    public function checkUserToken($token)
    {
        $result = json_decode(file_get_contents('https://graph.facebook.com/v2.10/me?access_token=' . $token), true);

        if (!isset($result['id'])) {
            abort(400, 'Token not found');
        }

        return $result;
    }
}
