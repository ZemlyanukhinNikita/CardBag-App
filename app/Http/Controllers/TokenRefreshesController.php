<?php namespace App\Http\Controllers;

use app\Repositories\AccessTokenInterface;
use app\Repositories\RefreshTokenInterface;
use app\Repositories\TokenInterface;
use Carbon\Carbon;
use Illuminate\Http\Request;

class TokenRefreshesController extends Controller
{
    public function refreshTokens(
        Request $request,
        RefreshTokenInterface $refreshTokenRepository,
        AccessTokenInterface $accessTokenRepository
    )
    {
        $refreshTokenFromRequest = $request->input('token');

        if (!$accessTokenModel = $accessTokenRepository->findOneBy([['uid', $request->input('uid')],
            ['network_id', $request->input('network_id')]])) {
            abort(401, 'Invalid data');
        }

        if ($accessTokenModel->refreshToken->name !== $refreshTokenFromRequest) {
            abort(400, 'Invalid data');
        }

        $newRefreshToken = bin2hex(openssl_random_pseudo_bytes(32));

        $newAccessToken = bin2hex(openssl_random_pseudo_bytes(64));

        $refreshTokenRepository->update('name', $refreshTokenFromRequest,
            [
                'name' => $newRefreshToken,
                'expires_at' => Carbon::now()->addMonth(6),
            ]);

        $accessTokenRepository->update('id', $accessTokenModel->id,
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

