<?php

namespace App\Service;

use App\Repositories\TokenInterface;
use app\Repositories\UserInterface;
use Illuminate\Http\Request;
use UserProfile;

class AuthorizeService implements SocialNetworkInterface
{
    private $request;
    private $userRepository;
    private $tokenRepository;
    private $factory;

    /**
     * VkAuthorizeService constructor.
     * @param Request $request
     * @param UserInterface $userRepository
     * @param TokenInterface $tokenRepository
     * @param SocialNetworkServiceFactory $factory
     */
    public function __construct(Request $request, UserInterface $userRepository,
                                TokenInterface $tokenRepository, SocialNetworkServiceFactory $factory)
    {
        $this->request = $request;
        $this->userRepository = $userRepository;
        $this->tokenRepository = $tokenRepository;
        $this->factory = $factory;
    }

    /**
     * Если пользователь существует в базе данных, возвращается модель пользователя
     * Иначе проверяется существование токена в соц сети
     * Если пользователя нет, добавляется новый
     * Если пользователь есть, но прошло дейтвие токена , токен обновляется
     * @param $uid
     * @param $token
     * @param $factory
     * @param $network
     * @return \Illuminate\Http\JsonResponse|mixed
     */
    public function auth($uid, $token, $network)
    {
        $user = $this->tokenRepository->findOneBy('uid', $uid);
        if ($user) {
            if ($user->token === $token && $user->network_id === (int)$this->request->input('network_id')) {

                $userModel = new UserProfile($this->userRepository->findOneBy('id', $user->user_id)->full_name,
                    $user->token, $user->uid);

                return response()->json([
                        'full_name' => $userModel->getFullName(),
                        'token' => $userModel->getToken(),
                        'uid' => $userModel->getUid(),
                    ]
                );
            }
        }
        
        $result = $this->factory->getUserSocialToken($network)->checkUserTokenInSocialNetwork($token, $uid);

        if (!$user) {
            return $this->registerNewUser($result);
        }

        return $this->refreshUserToken($result);
    }

    /**
     * Метод обновления токена пользователя
     * @param UserProfile $result
     * @return \Illuminate\Http\JsonResponse
     */
    public function refreshUserToken(UserProfile $result)
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
     * @param UserProfile $result
     * @return \Illuminate\Http\JsonResponse
     */
    public function registerNewUser(UserProfile $result)
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