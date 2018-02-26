<?php

namespace App\Service;

use app\Repositories\TokenInterface;
use app\Repositories\UserRepository;

class SocialNetworkServiceFactory
{
    private $userRepository;
    private $tokenRepository;
    private $checkTokenService;

    /**
     * AbstractNetworkFactory constructor.
     * @param UserRepository $userRepository
     * @param TokenInterface $tokenRepository
     * @param CheckTokenService $checkTokenService
     */
    public function __construct(UserRepository $userRepository,
                                TokenInterface $tokenRepository,
                                CheckTokenService $checkTokenService)
    {
        $this->userRepository = $userRepository;
        $this->tokenRepository = $tokenRepository;
        $this->checkTokenService = $checkTokenService;
    }

    public function getSocialNetwork($request, $network)
    {
        $service = "App\Service\\" . $network . "AuthorizeService";

        if (class_exists($service)) {
            return new $service($request, $this->userRepository,
                $this->tokenRepository, $this->checkTokenService);
        }
        abort(400, 'Bad request');
    }
}