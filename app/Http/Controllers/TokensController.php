<?php namespace App\Http\Controllers;

use app\Repositories\AccessTokenInterface;
use app\Repositories\NetworkInterface;
use app\Repositories\RefreshTokenInterface;
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
                              SocialNetworkFactory $factory,
                              UserRepository $userRepository, NetworkInterface $networkRepository)
    {
        $this->validateTokenFields($request);

        $networkId = $request->input('network_id');
        $uid = $request->input('uid');

        if ($userProfile = $factory->getSocialNetwork(
            $networkRepository->findOneBy([['id', $request->input('network_id')]])->name)
            ->checkUserTokenInSocialNetwork($request->input('token'), $uid)) {

        if($userModel = $userRepository->findOneBy([['network_id', $networkId],['uid', $uid]])) {
                $accessTokenModel = $accessTokenRepository->create([
                    'name' => bin2hex(openssl_random_pseudo_bytes(64)),
                    'user_id' => $userModel->id,
                    'expires_at' => Carbon::now()->addMinute(1440)
                ]);

                $refreshTokenModel = $refreshTokenRepository->create([
                    'name' => bin2hex(openssl_random_pseudo_bytes(32)),
                    'user_id' => $userModel->id
                ]);
        } else {
                $userModel = $userRepository->create([
                    'full_name' => $userProfile->getFullName(),
                    'uid' => $uid,
                    'network_id' => $networkId
                ]);

                $accessTokenModel = $accessTokenRepository->create([
                    'name' => bin2hex(openssl_random_pseudo_bytes(64)),
                    'user_id' => $userModel->id,
                    'expires_at' => Carbon::now()->addDay(1)
                ]);

                $refreshTokenModel = $refreshTokenRepository->create([
                    'name' => bin2hex(openssl_random_pseudo_bytes(32)),
                    'user_id' => $userModel->id
                ]);
            }

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

