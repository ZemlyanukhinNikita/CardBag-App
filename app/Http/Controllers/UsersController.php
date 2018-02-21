<?php namespace App\Http\Controllers;

use App\Service\FacebookAuthorizeService;
use App\Service\VkAuthorizeService;
use Illuminate\Http\Request;

class UsersController extends Controller
{
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

    public function getAuthorizedUser(Request $request, VkAuthorizeService $vkAuthorizeService,
                                      FacebookAuthorizeService $facebookAuthorizeService)
    {
        $this->validateCardFields($request);
//        return $vkAuthorizeService->authVk();
        return $facebookAuthorizeService->authFacebook();
    }
}
