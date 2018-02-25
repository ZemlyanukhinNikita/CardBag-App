<?php namespace App\Http\Controllers;

use app\Repositories\NetworkInterface;
use App\Service\SocialNetworkServiceFactory;
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

    public function getAuthorizedUser(Request $request, SocialNetworkServiceFactory $factory,
                                      NetworkInterface $networkRepository)
    {
        $this->validateCardFields($request);

        return $factory->getSocialNetwork($request, $networkRepository->
        findOneBy('id', $request->input('network_id'))->name)->auth($request->input('token'));
    }
}
