<?php namespace App\Http\Controllers;

use app\Repositories\NetworkInterface;
use app\Repositories\TokenInterface;
use app\Repositories\UserInterface;
use App\Service\AbstractNetworkFactory;
use Illuminate\Http\Request;

class UsersController extends Controller
{
    public static $network = 2;

    private function validateCardFields(Request $request)
    {
        $messages = [
            'network_id.exists' => 'Такой соц.сети в базе данных нет',
        ];

        $this->validate($request, [
            'network_id' => 'required|nullable|exists:networks,id',
            'uid' => 'required',
        ], $messages);
    }

    public function getAuthorizedUser(Request $request, UserInterface $userRepository, TokenInterface $tokenRepository, NetworkInterface $networkRepository)
    {
        $this->validateCardFields($request);
        return AbstractNetworkFactory::getSocialNetwork($request, $userRepository, $tokenRepository,
            $networkRepository, $request->input('network_id'))->auth();

    }
}
