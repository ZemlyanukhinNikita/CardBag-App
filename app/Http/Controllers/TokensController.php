<?php namespace App\Http\Controllers;

use app\Repositories\AccessTokenInterface;
use app\Repositories\NetworkInterface;
use app\Repositories\TokenInterface;
use app\Repositories\UserRepository;
use App\Service\SocialNetworkFactory;
use Illuminate\Http\Request;

class TokensController extends Controller
{
    private function validateCardFields(Request $request)
    {
        $messages = [
            'network_id.exists' => 'Такой соц.сети в базе данных нет',
        ];

        $this->validate($request, [
            'network_id' => 'integer|required|exists:networks,id',
            'token' => 'required',
            'uid' => 'required',
        ], $messages);
    }

    public function getTokens(Request $request, AccessTokenInterface $accessTokenRepository,
//                              RefreshTokenInterface $refreshTokenRepository,
                              TokenInterface $tokenRepository, SocialNetworkFactory $factory,
                              UserRepository $userRepository, NetworkInterface $networkRepository)
    {
        $this->validateCardFields($request);

        $networkId = $request->input('network_id');
        $uid = $request->input('uid');

        if ($tokenRepository->findOneBy([['uid', $uid], ['network_id', $networkId]])) {
            abort(400, 'есть uid');
        }

        if ($userProfile = $factory->getSocialNetwork(
            $networkRepository->findOneBy([['id', $request->input('network_id')]])->name)
            ->checkUserTokenInSocialNetwork($request->input('token'), $uid)) {

            //Генерим 2 токена и

            $accessToken = '123';
            $refreshToken = '321';

            $user = $userRepository->create(['full_name' => $userProfile->getFullName()]);

            $socialNetwork = $tokenRepository->create(['user_id' => $user->id, 'uid' => $uid, 'network_id' => $networkId]);

            $tokenModel = $accessTokenRepository->create([
                'uid_id' => $socialNetwork->id,
                'name' => $accessToken
            ]);

//                $refreshTokenRepository->create([
//                    'access_token_id' => $tokenModel->id,
//                    'name' => $refreshToken
//                ]);

            return response()->json([
                'full_name' => $userProfile->getFullName(),
                'access_token' => $accessToken,
                'refresh_token' => $refreshToken,
                'uid' => $userProfile->getUid()
            ]);
        }
        abort(400, 'invalid data');
    }
}

