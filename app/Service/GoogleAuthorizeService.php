<?php

namespace app\Service;


use app\Repositories\NetworkInterface;
use app\Repositories\TokenInterface;
use app\Repositories\UserInterface;
use GuzzleHttp\Promise;
use Illuminate\Http\Request;

class GoogleAuthorizeService implements SocialNetworkInterface
{
    private $request;
    private $userRepository;
    private $tokenRepository;
    private $networkRepository;

    /**
     * GoogleAuthorizeService constructor.
     * @param Request $request
     * @param UserInterface $userRepository
     * @param TokenInterface $tokenRepository
     * @param NetworkInterface $networkRepository
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

    /**
     * Метод который авторизует пользователя если токен и идентификатор пользотваеля существуют в бд,
     * регистриурет пользователя когда токен имеется в соцсети, а идентификатора пользователя нет в бд,
     * обновляет токен пользователя, если идентификатор пользователя есть в бд, а токен не совпадает
     * @return mixed
     */
    public function authorizeWithGoogle()
    {
        if ($user = $this->userRepository->findOneBy('uid', $this->request->input('uid'))) {
            $token = $this->tokenRepository->findOneBy('id', $user->token);

            if ($token->token !== $this->request->input('token')) {
                $this->refreshUserToken($user, $token);
            }
        } else {
            $this->registerNewUser();
        }

        $user = $this->userRepository->findOneBy('uid', $this->request->input('uid'));
        $user->token = $user->tokenName->token;
        return $user;
    }

    /**
     * Метод обновляет токен пользователя на новый в базе данных
     * @param $user
     * @param $token
     */
    public function refreshUserToken($user, $token)
    {
        $result = $this->checkUserToken($this->request->input('token'));

        if ((string)$result['id'] === $this->request->input('uid')) {
            $this->tokenRepository->update('id', $user->token, ['token' => $this->request->input('token')]);
        } else {
            abort(400, 'Uid do not match');
        }
    }

    /**
     * Метод добавляет нового пользователя в базе данных
     */
    public function registerNewUser()
    {
        $result = $this->checkUserToken($this->request->input('token'));
        if ($this->tokenRepository->findOneBy('token', $this->request->input('token'))) {
            abort(400, 'Token must be unique');
        }
        if ($result['id'] === $this->request->input('uid')) {
            $this->tokenRepository->create([
                'token' => $this->request->input('token'),
                'network_id' => $this->request->input('network_id'),
            ]);

            $this->userRepository->create([
                'uid' => $this->request->input('uid'),
                'full_name' => $result['name']['givenName'] . ' ' . $result['name']['familyName'],
                'token' => $this->tokenRepository->findOneBy('token', $this->request->input('token'))->id
            ]);
        } else {
            abort(400, 'Uid do not match');
        }
    }

    /**
     * Метод проверяет существует ли такой токен в соц-сети, если да
     * @param $token
     * @return mixed
     */
    public function checkUserToken($token)
    {
        if ($result = json_decode(@file_get_contents('https://www.googleapis.com/plus/v1/people/me?access_token=' . $token),
            true)
        ) {
            return $result;
        }
        abort(400, 'Token not found');
    }
}