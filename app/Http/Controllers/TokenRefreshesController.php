<?php namespace App\Http\Controllers;

use app\Repositories\AccessTokenInterface;
use app\Repositories\RefreshTokenInterface;
use Illuminate\Http\Request;

class TokenRefreshesController extends Controller
{

    public function refreshTokens(
        Request $request,
        RefreshTokenInterface $refreshTokenRepository,
        AccessTokenInterface $accessTokenRepository
    ) {
        $this->validate($request, [
            'refreshToken' => 'required'
        ]);

        $refreshTokenFromRequest = $request->input('refresh_token');
        if (!$refreshToken = $refreshTokenRepository->findOneBy([['name', $refreshTokenFromRequest]])) {
            abort(400, 'Invalid refresh token');
        }
        // тут будем генерировать токены
        $newAccessToken = 123;
        $newRefreshToken = 321;
        $refreshTokenRepository->update('name', $refreshTokenFromRequest,
            [
                'refresh_token' => $newRefreshToken
            ]);
        $accessTokenRepository->update('id', $refreshToken->access_token_id,
            [
                'access_token' => $newAccessToken
            ]);
        return response()->json([
            'access_token' => $newAccessToken,
            'refresh_token' => $newRefreshToken,
        ]);
    }

}
