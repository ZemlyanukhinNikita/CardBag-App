<?php

namespace app\Service;

use app\Repositories\NetworkInterface;
use app\Repositories\TokenInterface;
use app\Repositories\UserInterface;
use Illuminate\Http\Request;

class FirebaseAuthorizeService implements SocialNetworkInterface
{
    private $request;
    private $userRepository;
    private $tokenRepository;
    private $networkRepository;

    /**
     * FirebaseAuthorizeService constructor.
     * @param $request
     * @param $userRepository
     * @param $tokenRepository
     * @param $networkRepository
     */
    public function __construct(
        Request $request,
        UserInterface $userRepository,
        TokenInterface $tokenRepository,
        NetworkInterface $networkRepository
    ) {
        $this->request = $request;
        $this->userRepository = $userRepository;
        $this->tokenRepository = $tokenRepository;
        $this->networkRepository = $networkRepository;
    }

    public function authorizeWithFirebase()
    {
        
    }

    public function refreshUserToken($user, $token)
    {
        // TODO: Implement refreshUserToken() method.
    }

    public function registerNewUser()
    {
        // TODO: Implement registerNewUser() method.
    }

    public function checkUserToken($token)
    {
        // TODO: Implement checkUserToken() method.
    }
}