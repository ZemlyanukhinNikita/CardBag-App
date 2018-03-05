<?php namespace App\Http\Controllers;

use app\Repositories\NetworkInterface;
use App\Service\AuthorizeService;
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
        NetworkInterface $networkRepository,
        AuthorizeService $authorizeService
    )
    {
        $this->validateCardFields($request);

        if (!$userModel = $authorizeService->authorizeWithSocialNetwork($request->input('uid'),
            $request->input('token'),
            $networkRepository->findOneBy([['id', $request->input('network_id')]])->name)
        ) {
            abort(400, 'Invalid data');
        }

        return response()->json([
            'full_name' => $userModel->getFullName(),
            'token' => $userModel->getToken(),
            'uid' => $userModel->getUid()
        ]);
    }
}
