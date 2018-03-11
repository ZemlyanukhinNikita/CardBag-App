<?php namespace App\Http\Controllers;

use app\Repositories\TokenInterface;
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
            'access_token' => 'required',
            'uid' => 'required',
        ], $messages);
    }

    public function getAuthorizedUser(
        Request $request,
        TokenInterface $tokenRepository
    )
    {
        $this->validateCardFields($request);

        if (!$tokenModel = $tokenRepository->findOneBy([['uid', $request->input('uid')],
            ['network_id', $request->input('network_id')]])) {
            abort(401, 'Invalid data');
        }

        return response()->json([
            'full_name' => $tokenModel->user->full_name,
            'access_token' => $tokenModel->access_token->name,
            'uid' => $tokenModel->uid
        ]);
    }

}
