<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.01.18
 * Time: 22:01
 */

namespace App\Service;


use Illuminate\Http\Request;
use Repositories\CardRepository;

class CardService
{
    private $cardRepository;

    /**
     * CardService constructor.
     * @param Request $request
     * @param CardRepository $cardRepository
     */
    private function __construct(Request $request, CardRepository $cardRepository)
    {
        $this->cardRepository = $cardRepository;
    }

    private function isUserExist(): bool
    {
        $data = $this->cardRepository->getUserUuid();
        if (!empty($data)) {
            return true;
        }
        return false;
    }

    public function checkUserCards(CardGenerateService $cardGenerateService)
    {
        if ($this->isUserExist()) {
            $data = $this->cardRepository->getAllUsersCards();
            return $data;
        } else {
            $this->cardRepository->addUserUuidToDb();
            $cardGenerateService->addUserCards();
            $data = $this->cardRepository->getAllUsersCards();
            return $data;
        }
    }
}