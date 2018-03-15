<?php namespace App\Http\Controllers;

use app\Repositories\AccessTokenInterface;
use app\Repositories\RefreshTokenInterface;
use App\Repositories\UserDataInterface;
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
        UserDataInterface $userDataRepository
    ) {
        $this->validateTokenFields($request);

        $refreshTokenFromRequest = $request->input('token');

        if (!$userDataRepository->findOneBy([
            ['uid', $request->input('uid')],
            ['network_id', $request->input('network_id')]
        ])
        ) {
            abort(401, 'Invalid data');
        }

        if (!$refreshTokenModel = $refreshTokenRepository->findOneBy([['refresh_token', $refreshTokenFromRequest]])) {
            abort(400, 'Invalid data');
        }

        $newRefreshToken = bin2hex(openssl_random_pseudo_bytes(32));

        $newAccessToken = bin2hex(openssl_random_pseudo_bytes(64));

        $refreshTokenRepository->create(
            [
                'refresh_token' => $newRefreshToken,
                'expires_at' => Carbon::now(),
                'access_token_id' => $refreshTokenModel->id
            ]);
        $refreshTokenRepository->update('refresh_token', $refreshTokenFromRequest, [
            'expires_at' => Carbon::now()
        ]);

        $accessTokenRepository->create(
            [
                'user_id' => $refreshTokenModel->user_id,
                'access_token' => $newAccessToken,
                'expires_at' => Carbon::now()->addDay(1),
            ]);

        return response()->json([
            'access_token' => $newAccessToken,
            'refresh_token' => $newRefreshToken,
        ]);
    }
}

