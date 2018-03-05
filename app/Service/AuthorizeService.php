<?php

namespace App\Service;

use App\Repositories\TokenInterface;
use app\Repositories\UserInterface;
use Illuminate\Http\Request;
use UserProfile;

class AuthorizeService
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
     * @param SocialNetworkFactory $factory
     */
    public function __construct(
        Request $request,
        UserInterface $userRepository,
        TokenInterface $tokenRepository,
        SocialNetworkFactory $factory
    )
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
     * @param $network
     * @return false | UserProfile
     */
    public function authorizeWithSocialNetwork($uid, $token, $network)
    {
        $socialNetwork = $this->tokenRepository->findOneBy([
            ['uid', $uid],
            [
                'network_id',
                $this->request->input('network_id')
            ]
        ]);

        if (!$socialNetwork) {

            if (!$userModel = $this->factory->getSocialNetwork($network)->checkUserTokenInSocialNetwork($token,
                $uid)
            ) {
                return false;
            }
            $this->registerNewUser($userModel);

        } elseif ($socialNetwork->token === $token) {
            $userModel = new UserProfile($socialNetwork->user->full_name,
                $socialNetwork->token, $socialNetwork->uid);

        } elseif ($socialNetwork->token !== $token) {

            if (!$userModel = $this->factory->getSocialNetwork($network)->checkUserTokenInSocialNetwork($token,
                $uid)
            ) {
                return false;
            }
            $this->refreshUserToken($userModel);
        }
        /** @var UserProfile $userModel */
        return $userModel;
    }

    /**
     * Метод обновления токена пользователя
     * @param UserProfile $result
     */
    private function refreshUserToken(UserProfile $result)
    {
        $this->tokenRepository->update('uid', $result->getUid(),
            ['token' => $result->getToken()]);
    }

    /**
     * Метод регистрации нового пользователя
     * @param UserProfile $result
     */
    private function registerNewUser(UserProfile $result)
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
    }
}
