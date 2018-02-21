<?php

namespace App\Service;


use app\Repositories\UserInterface;
use Illuminate\Http\Request;

class VkAuthorizeService implements SocialNetworkInterface
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

    public function authVk()
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

            if ((string)$result['response'][0]['uid'] === $this->request->input('uid')) {
                $this->userRepository->update('uid', $this->request->input('uid'), ['uuid' => $this->request->input('token')]);
                return $this->userRepository->findOneBy('uid', $this->request->input('uid'));
            }
            abort(400, 'Uid do not match');
        }
    }


    public function registerNewUser($user)
    {
        $result = $this->checkUserToken($this->request->input('token'));

        if ($this->userRepository->findOneBy('uuid', $this->request->input('token'))
        ) {
            abort(400, 'Token must be unique');
        }

        if ((string)$result['response'][0]['uid'] === $this->request->input('uid')) {
            $this->userRepository->create([
                'uuid' => $this->request->input('token'),
                'uid' => $this->request->input('uid'),
                'full_name' => $result['response'][0]['first_name'] . ' ' . $result['response'][0]['last_name'],
                'network_id' => $this->request->input('network'),
            ]);
            return $this->userRepository->findOneBy('uid', $this->request->input('uid'));
        }
        abort(400, 'Uid do not match');
    }


    public function checkUserToken($token)
    {
        $result = json_decode(file_get_contents('https://api.vk.com/method/users.get?&access_token=' . $token), true);

        if (!isset($result['response'])) {
            abort(400, 'Token not found');
        }

        return $result;
    }
}