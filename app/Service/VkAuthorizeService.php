<?php

namespace App\Service;

use App\Repositories\TokenInterface;
use app\Repositories\UserInterface;
use App\User;
use Illuminate\Http\Request;

class VkAuthorizeService implements SocialNetworkInterface
{
    private $request;
    private $userRepository;
    private $tokenRepository;
    private $checkTokenService;

    /**
     * VkAuthorizeService constructor.
     * @param Request $request
     * @param UserInterface $userRepository
     * @param TokenInterface $tokenRepository
     * @param CheckTokenService $vkCheckTokenService
     */
    public function __construct($request, $userRepository,
                                $tokenRepository,
                                $vkCheckTokenService)
    {
        $this->request = $request;
        $this->userRepository = $userRepository;
        $this->tokenRepository = $tokenRepository;
        $this->checkTokenService = $vkCheckTokenService;
    }

    /**
     * Если пользователь существует в базе данных, возвращается модель пользователя
     * Иначе проверяется существование токена в соц сети
     * Если пользователя нет, добавляется новый
     * Если пользователь есть, но прошло дейтвие токена , токен обновляется
     * @param $token
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function auth($token)
    {
        $userModel = new User();
        $user = $this->tokenRepository->findOneBy('uid', $this->request->input('uid'));
        if ($user) {
            if ($user->token === $token && $user->network_id === (int)$this->request->input('network_id')) {

                $userModel->setFullName($this->userRepository->findOneBy('id', $user->user_id)->full_name);
                $userModel->setToken($user->token);
                $userModel->setUid($user->uid);

                return response()->json([
                        'full_name' => $userModel->getFullName(),
                        'token' => $userModel->getToken(),
                        'uid' => $userModel->getUid(),
                    ]
                );
            }
        }

        $result = $this->checkTokenService->checkUserTokenInVk($token, $userModel);

        if (!$user) {
            return $this->registerNewUser($result);
        }

        return $this->refreshUserToken($result);
    }

    /**
     * Метод обновления токена пользователя
     * @param User $result
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshUserToken(User $result)
    {
        $this->tokenRepository->update('uid', $result->getUid(),
            ['token' => $result->getToken()]);

        return response()->json([
                'full_name' => $result->getFullName(),
                'token' => $result->getToken(),
                'uid' => $result->getUid(),
            ]
        );
    }

    /**
     * Метод регистрации нового пользователя
     * @param User $result
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerNewUser(User $result)
    {
        $user = $this->userRepository->create([
            'full_name' => $result->getFullName(),
        ]);

        $this->tokenRepository->create([
            'token' => $result->getToken(),
            'network_id' => $this->request->input('network_id'),
            'uid' => $result->getUid(),
            'user_id' => $user->id,
        ]);

        return response()->json([
                'full_name' => $result->getFullName(),
                'token' => $result->getToken(),
                'uid' => $result->getUid(),
            ]
        );
    }
}