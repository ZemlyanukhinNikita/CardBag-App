<?php namespace App\Http\Controllers;

use App\Service;
use App\Service\CardService;
use Exception;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Http\Request;

class CardsController extends Controller
{
    /**
     * @param Request $request
     * @param CardService $cardService
     * @return \Illuminate\Http\JsonResponse
     */
    public function getAllUserCards(Request $request, CardService $cardService)
    {
        try {
            /** @var Collection $cards */
            if (!count($cards = $cardService->getUserCards($request->header('uuid')))) {
                return response()->json([], 204);
            }
            return response()->json($cards->load('category')->makeHidden(['user_id', 'category_id', 'created_at', 'updated_at']));
        } catch (Exception $e) {
            return response()->json(['status' => '500', 'message' => 'Internal server error'], 500);
        }
    }
}
