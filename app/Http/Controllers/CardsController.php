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
            if (!count($cards = $cardService->checkUserCards($cardGenerateService))) {
                return response()->json(['message' => 'No content', 'status' => '204'], 200);
            }
        } catch (Exception $e) {
            return response()->json(['message' => 'Internal server error', 'status' => '500'], 500);
        }
        return response()->json($cards, 200);
    }
}
