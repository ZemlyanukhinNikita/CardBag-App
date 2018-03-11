<?php namespace App\Http\Controllers;

use app\Repositories\AccessTokenInterface;
use app\Repositories\NetworkInterface;
use app\Repositories\RefreshTokenInterface;
use app\Repositories\TokenInterface;
use app\Repositories\UserRepository;
use App\Service\SocialNetworkFactory;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TokensController extends Controller
{
    private function validateTokenFields(Request $request)
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
                              RefreshTokenInterface $refreshTokenRepository,
                              TokenInterface $tokenRepository, SocialNetworkFactory $factory,
                              UserRepository $userRepository, NetworkInterface $networkRepository)
    {
        $this->validateTokenFields($request);

        $networkId = $request->input('network_id');
        $uid = $request->input('uid');

        if ($tokenRepository->findOneBy([['uid', $uid], ['network_id', $networkId]])) {
            abort(400, 'You have already registered this user');
        }

        if ($userProfile = $factory->getSocialNetwork(
            $networkRepository->findOneBy([['id', $request->input('network_id')]])->name)
            ->checkUserTokenInSocialNetwork($request->input('token'), $uid)) {

            $userModel = $userRepository->create(['full_name' => $userProfile->getFullName()]);

            $tokenModel = $tokenRepository->create(['user_id' => $userModel->id, 'uid' => $uid, 'network_id' => $networkId]);

            $accessTokenModel = $accessTokenRepository->create([
                'uid_id' => $tokenModel->id,
                'name' => bin2hex(openssl_random_pseudo_bytes(64)),
                'user_id' => $userModel->id,
                'expires_at' => Carbon::now()->addMinute(1440)
            ]);

            $refreshTokenModel = $refreshTokenRepository->create([
                'access_token_id' => $accessTokenModel->id,
                'name' => bin2hex(openssl_random_pseudo_bytes(32)),
                'expires_at' => Carbon::now()->addMonth(6)
            ]);

            return response()->json([
                'full_name' => $userProfile->getFullName(),
                'access_token' => $accessTokenModel->name,
                'refresh_token' => $refreshTokenModel->name,
                'uid' => $userProfile->getUid()
            ]);
        }
        abort(400, 'invalid data');
    }
}

