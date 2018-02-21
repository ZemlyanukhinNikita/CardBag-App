<?php

namespace App\Service;


use App\Repositories\NetworkInterface;
use App\Repositories\TokenInterface;
use app\Repositories\UserInterface;
use Illuminate\Http\Request;

class VkAuthorizeService implements SocialNetworkInterface
{
    private $request;
    private $userRepository;
    private $tokenRepository;
    private $networkRepository;

    /**
     * VkAuthorizeService constructor.
     * @param Request $request
     * @param UserInterface $userRepository
     * @param TokenInterface $tokenRepository
     * @param NetworkInterface $networkRepository
     */
    public function __construct(Request $request, UserInterface $userRepository, TokenInterface $tokenRepository, NetworkInterface $networkRepository)
    {
        $this->request = $request;
        $this->userRepository = $userRepository;
        $this->tokenRepository = $tokenRepository;
        $this->networkRepository = $networkRepository;
    }

    public function authVk()
    {
        $user = $this->userRepository->findOneBy('uid', $this->request->input('uid'));
        if ($user) {
            $token = $this->tokenRepository->findOneBy('id', $user->token);

            if ($token->token === $this->request->input('token')) {
                $user->token = $user->tokenName->token;
                return response()->json($user);
            }

            return $this->refreshUserToken($user, $token);
        }
        return $this->registerNewUser();
    }

    public function refreshUserToken($user, $token)
    {
        if ($token->token !== $this->request->input('token')) {

            $result = $this->checkUserToken($this->request->input('token'));

            if ((string)$result['response'][0]['uid'] === $this->request->input('uid')) {
                $this->tokenRepository->update('id', $user->token, ['token' => $this->request->input('token')]);
                $user = $this->userRepository->findOneBy('uid', $this->request->input('uid'));
                $user->token = $user->tokenName->token;
                return $user;
            }
            abort(400, 'Uid do not match');
        }
    }


    public function registerNewUser()
    {
        $result = $this->checkUserToken($this->request->input('token'));

        if ($this->tokenRepository->findOneBy('token', $this->request->input('token'))
        ) {
            abort(400, 'Token must be unique');
        }

        if ((string)$result['response'][0]['uid'] === $this->request->input('uid')) {
            $this->tokenRepository->create([
                'token' => $this->request->input('token'),
                'network_id' => $this->request->input('network_id'),
            ]);

            $this->userRepository->create([
                'uid' => $this->request->input('uid'),
                'full_name' => $result['response'][0]['first_name'] . ' ' . $result['response'][0]['last_name'],
                'token' => $this->tokenRepository->findOneBy('token', $this->request->input('token'))->id
            ]);

            $user = $this->userRepository->findOneBy('uid', $this->request->input('uid'));
            $user->token = $user->tokenName->token;
            return $user;
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