<?php

namespace App\Service;


use app\Repositories\TokenInterface;
use app\Repositories\UserInterface;
use App\User;
use Illuminate\Http\Request;


class FirebaseAuthorizeService implements SocialNetworkInterface
{
    private $request;
    private $userRepository;
    private $tokenRepository;
    private $checkTokenService;

    /**
     * FirebaseAuthorizeService constructor.
     * @param Request $request
     * @param UserInterface $userRepository
     * @param TokenInterface $tokenRepository
     * @param CheckTokenService $checkTokenService
     */
    public function __construct(
        $request,
        $userRepository,
        $tokenRepository,
        $checkTokenService
    ) {
        $this->request = $request;
        $this->userRepository = $userRepository;
        $this->tokenRepository = $tokenRepository;
        $this->checkTokenService = $checkTokenService;
    }


    /**
     * Метод обновления токена пользователя
     * если у пользователя прошло действие токена
     * @param User $result
     * @return mixed
     */
    public function refreshUserToken(User $result)
    {
        $this->tokenRepository->update('uid', $this->request->input('uid'),
            ['token' => $this->request->input('token')]);

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
     * @return mixed
     */
    public function registerNewUser(User $result)
    {
        if ($this->tokenRepository->findOneBy('token', $this->request->input('token'))
        ) {
            abort(400, 'Token must be unique');
        }
        $user = $this->userRepository->create([
            'full_name' => $result->getFullName(),
        ]);

        $this->tokenRepository->create([
            'token' => $this->request->input('token'),
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

    /**
     * Метод авторизация пользователя
     * @param $token
     * @return mixed
     */
    public function auth($token)
    {
        $user = $this->tokenRepository->findOneBy('uid', $this->request->input('uid'));
        $userModel = new User;
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

        $result = $this->checkTokenService->checkUserTokenInFirebase($token, $userModel);

        if (!$user) {
            return $this->registerNewUser($result);
        }

        return $this->refreshUserToken($result);
    }
}