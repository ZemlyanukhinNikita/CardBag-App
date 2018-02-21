<?php

namespace App\Service;


use App\Repositories\NetworkInterface;
use App\Repositories\TokenInterface;
use app\Repositories\UserInterface;
use Illuminate\Http\Request;

class FacebookAuthorizeService implements SocialNetworkInterface
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

    public function authFacebook()
    {
        $user = $this->userRepository->findOneBy('uid', $this->request->input('uid'));
        if ($user) {
            $token = $this->tokenRepository->findOneBy('id', $user->token);

            if ($token->token === $this->request->header('token')) {
                $user->token = $user->tokenName->token;
                return response()->json($user);
            }

            return $this->refreshUserToken($user, $token);
        }
        return $this->registerNewUser();
    }

    public function refreshUserToken($user, $token)
    {
        if ($token->token !== $this->request->header('token')) {

            $result = $this->checkUserToken($this->request->header('token'));

            if ((string)$result['id'] === $this->request->input('uid')) {
                $this->tokenRepository->update('id', $user->token, ['token' => $this->request->header('token')]);
                $user = $this->userRepository->findOneBy('uid', $this->request->input('uid'));
                $user->token = $user->tokenName->token;
                return $user;
            }
            abort(400, 'Uid do not match');
        }
    }


    public function registerNewUser()
    {
        $result = $this->checkUserToken($this->request->header('token'));

        if ($this->tokenRepository->findOneBy('token', $this->request->header('token'))
        ) {
            abort(400, 'Token must be unique');
        }

        if ((string)$result['id'] === $this->request->input('uid')) {
            $this->tokenRepository->create([
                'token' => $this->request->header('token'),
                'network_id' => $this->request->input('network_id'),
            ]);

            $this->userRepository->create([
                'uid' => $this->request->input('uid'),
                'full_name' => $result['name'],
                'token' => $this->tokenRepository->findOneBy('token', $this->request->header('token'))->id
            ]);

            $user = $this->userRepository->findOneBy('uid', $this->request->input('uid'));
            $user->token = $user->tokenName->token;
            return $user;
        }
        abort(400, 'Uid do not match');
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