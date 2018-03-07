<?php namespace App\Http\Controllers;

use app\Repositories\TokenInterface;
use app\Repositories\UserInterface;
use Illuminate\Http\Request;

class UsersController extends Controller
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

    public function getAuthorizedUser(
        Request $request,
        TokenInterface $tokenRepository,
        UserInterface $userRepository,
        AccessTokenInterface $accessTokenRepository
    ) {
        $this->validateCardFields($request);

        $token = $tokenRepository->findOneBy([['uid', $request->input('uid')]]);
        $userModel = $userRepository->findOneBy([['id', $token->user_id]]);
        $tokenModel = $accessTokenRepository->findOneBy([['uid_id', $token->id]]);
        
        return response()->json([
            'full_name' => $userModel->full_name,
            'token' => $token->name,
            'uid' => $tokenModel->uid
        ]);
    }
}
