<?php namespace App\Http\Controllers;


use app\Repositories\UserInterface;
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
        UserInterface $userRepository)
    {
        $this->validateFields($request);

        if (!$userModel = $userRepository->findOneBy([['uid', $request->input('uid')],
            ['network_id', $request->input('network_id')]])) {
            abort(401, 'Invalid data');
        }

        return response()->json([
            'full_name' => $userModel->full_name,
            'access_token' => $request->header('token'),
            'uid' => $userModel->uid
        ]);
    }

}
