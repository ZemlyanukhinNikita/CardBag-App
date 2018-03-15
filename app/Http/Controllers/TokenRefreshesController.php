<?php namespace App\Http\Controllers;

use app\Repositories\AccessTokenInterface;
use app\Repositories\RefreshTokenInterface;
use App\Repositories\UserNetworkInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TokenRefreshesController extends Controller
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

    public function refreshTokens(
        Request $request,
        RefreshTokenInterface $refreshTokenRepository,
        AccessTokenInterface $accessTokenRepository,
        UserNetworkInterface $userNetworkRepository
    ) {
        $this->validateTokenFields($request);

        $refreshTokenFromRequest = $request->input('token');

        if (!$userData = $userNetworkRepository->findOneBy([
            ['user_identity', $request->input('uid')],
            ['network_id', $request->input('network_id')]
        ])
        ) {
            abort(401, 'Invalid data');
        }

        if (!$refreshTokenModel = $refreshTokenRepository->findOneBy([
            ['refresh_token', $refreshTokenFromRequest],
            ['expires_at', '!=', null],
            ['expires_at', '>', Carbon::now()]
        ])
        ) {
            abort(400, 'Token expired');
        }

        $newRefreshToken = bin2hex(openssl_random_pseudo_bytes(32));

        $newAccessToken = bin2hex(openssl_random_pseudo_bytes(64));

        $accessToken = $accessTokenRepository->create(
            [
                'user_id' => $userData->user_id,
                'access_token' => $newAccessToken,
                'expires_at' => Carbon::now()->addDay(1),
            ]);

        $refreshTokenRepository->create(
            [
                'refresh_token' => $newRefreshToken,
                'expires_at' => Carbon::now()->addMonths(6),
                'access_token_id' => $accessToken->id
            ]);

        $refreshTokenRepository->update('refresh_token', $refreshTokenFromRequest, [
            'expires_at' => Carbon::now()
        ]);

        return response()->json([
            'access_token' => $newAccessToken,
            'refresh_token' => $newRefreshToken,
        ]);
    }
}

