<?php namespace App\Http\Controllers;

use app\Repositories\AccessTokenInterface;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    private function validateFields(Request $request)
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
        AccessTokenInterface $accessTokenRepository)
    {
        $this->validateFields($request);

        if (!$accessTokenModel = $accessTokenRepository->findOneBy([['uid', $request->input('uid')],
            ['network_id', $request->input('network_id')]])) {
            abort(401, 'Invalid data');
        }

        return response()->json([
            'full_name' => $accessTokenModel->user->full_name,
            'access_token' => $accessTokenModel->name,
            'uid' => $accessTokenModel->uid
        ]);
    }

}
