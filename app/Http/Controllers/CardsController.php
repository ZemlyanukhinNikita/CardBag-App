<?php namespace App\Http\Controllers;

use App\Service;
use App\Service\CardGenerateService;
use App\Service\CardService;
use Exception;


class CardsController extends Controller
{

    /**
     * @param CardService $cardService
     * @param CardGenerateService $cardGenerateService
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllUserCards(CardService $cardService, CardGenerateService $cardGenerateService)
    {
        try {
            $cards = $cardService->checkUserCards($cardGenerateService);
            if (count($cards)) {
                return response()->json($cards, 200);
            } else {
                return response()->json(['message' => 'no content', 'status' => '204']);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'Server error', 'status' => '500'], 500);
        }
    }
}