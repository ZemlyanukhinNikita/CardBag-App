<?php namespace App\Http\Controllers;

use App\Service;
use App\Service\CardGenerateService;
use App\Service\CardService;

class CardsController extends Controller
{
    /**
     * @param CardService $cardService
     * @param CardGenerateService $cardGenerateService
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllUserCards(CardService $cardService, CardGenerateService $cardGenerateService)
    {
        $cards = $cardService->checkUserCards($cardGenerateService);
        if ($cards !== null) {
            return response()->json($cards, 200);
        } else {
            return response()->json(['status' => '204', 'message' => 'No content']);
        }
    }
}