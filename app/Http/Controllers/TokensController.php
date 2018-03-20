<?php namespace App\Http\Controllers;

use app\Repositories\AccessTokenInterface;
use app\Repositories\NetworkInterface;
use app\Repositories\RefreshTokenInterface;
use App\Repositories\UserNetworkInterface;
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

    public function getTokens(
        Request $request,
        AccessTokenInterface $accessTokenRepository,
        RefreshTokenInterface $refreshTokenRepository,
        SocialNetworkFactory $factory,
        UserRepository $userRepository,
        NetworkInterface $networkRepository,
        UserNetworkInterface $userNetworkRepository
    ) {
        $this->validateTokenFields($request);

        $networkId = $request->input('network_id');
        $uid = $request->input('uid');

        if ($userProfile = $factory->getSocialNetwork(
            $networkRepository->findOneBy([['id', $request->input('network_id')]])->name)
            ->checkUserTokenInSocialNetwork($request->input('token'), $uid)
        ) {

            if ($userModel = $userNetworkRepository->findOneBy([['network_id', $networkId], ['user_identity', $uid]])) {
                $accessTokenModel = $accessTokenRepository->create([
                    'access_token' => bin2hex(openssl_random_pseudo_bytes(64)),
                    'user_id' => $userModel->user_id,
                    'expires_at' => Carbon::now()->addDay(1)
                ]);

                $refreshTokenModel = $refreshTokenRepository->create([
                    'refresh_token' => bin2hex(openssl_random_pseudo_bytes(32)),
                    'access_token_id' => $accessTokenModel->id,
                    'expires_at' => Carbon::now()->addMonths(6)
                ]);
            } else {
                $userModel = $userRepository->create([
                    'full_name' => $userProfile->getFullName(),
                ]);

                $userNetworkRepository->create([
                    'user_identity' => $uid,
                    'network_id' => $networkId,
                    'user_id' => $userModel->id
                ]);

                $accessTokenModel = $accessTokenRepository->create([
                    'access_token' => bin2hex(openssl_random_pseudo_bytes(64)),
                    'user_id' => $userModel->id,
                    'expires_at' => Carbon::now()->addDay(1)
                ]);

                $refreshTokenModel = $refreshTokenRepository->create([
                    'refresh_token' => bin2hex(openssl_random_pseudo_bytes(32)),
                    'access_token_id' => $accessTokenModel->id,
                    'expires_at' => Carbon::now()->addMonths(6)
                ]);
            }

            return response()->json([
                'full_name' => $userProfile->getFullName(),
                'access_token' => $accessTokenModel->access_token,
                'refresh_token' => $refreshTokenModel->refresh_token,
                'uid' => $userProfile->getUid()
            ]);
        }
        abort(400, 'invalid data');
    }
}

