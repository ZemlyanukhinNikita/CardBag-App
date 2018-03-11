<?php namespace App\Http\Controllers;

use app\Repositories\AccessTokenInterface;
use app\Repositories\RefreshTokenInterface;
use app\Repositories\TokenInterface;
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
            'refresh_token' => 'required',
            'uid' => 'required',
        ], $messages);
    }

    public function refreshTokens(
        Request $request,
        RefreshTokenInterface $refreshTokenRepository,
        AccessTokenInterface $accessTokenRepository,
        TokenInterface $tokenRepository
    )
    {
        $this->validateTokenFields($request);

        $refreshTokenFromRequest = $request->input('refresh_token');

        if (!$tokenRepository->findOneBy([['uid', $request->input('uid')],
            ['network_id', $request->input('network_id')]])) {
            abort(401, 'Invalid data');
        }

        if (!$refreshToken = $refreshTokenRepository->findOneBy([['name', $refreshTokenFromRequest]])) {
            abort(400, 'Invalid data');
        }

        $newRefreshToken = bin2hex(openssl_random_pseudo_bytes(32));

        $newAccessToken = bin2hex(openssl_random_pseudo_bytes(64));

        $refreshTokenRepository->update('name', $refreshTokenFromRequest,
            [
                'name' => $newRefreshToken,
                'expires_at' => Carbon::now()->addMonth(6),
            ]);

        $accessTokenRepository->update('id', $refreshToken->access_token_id,
            [
                'name' => $newAccessToken,
                'expires_at' => Carbon::now()->addMinute(1440),
            ]);

        return response()->json([
            'access_token' => $newAccessToken,
            'refresh_token' => $newRefreshToken,
        ]);
    }
}

