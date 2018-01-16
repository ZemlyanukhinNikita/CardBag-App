<?php namespace App\Http\Controllers;

use App\Service;

class CardsController extends Controller
{
    private $cardService;

    /**
     * @param CardService $cardService
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllUserCards(CardService $cardService)
    {
        $cards = $cardService->checkUserCards();
        if ($cards !== null) {
            return response()->json($cards, 200);
        } else {
            return response()->json(['status' => '204', 'message' => 'No content']);
        }
    }
}