<?php

namespace App\Service;

use Illuminate\Http\Request;
use Repositories\CardRepository;

class CardService
{
    public $cardRepository;

    /**
     * CardService constructor.
     * @param Request $request
     * @param CardRepository $cardRepository
     */
    public function __construct(Request $request, CardRepository $cardRepository)
    {
        $this->cardRepository = $cardRepository;
    }

    /**
     * Проверка на существование UUID в базе данных
     * @return bool
     */
    private function isUserExist()
    {
        $data = $this->cardRepository->getUserUuid();
        if ($data) {
            return true;
        }
        return false;
    }

    /**
     * Вокврат карточек если UUID существует в базе данных
     * иначе добавить UUID в базу и сгененировать случайное число карт
     * @param CardGenerateService $cardGenerateService
     * @return static
     */
    public function checkUserCards(CardGenerateService $cardGenerateService)
    {
        if ($this->isUserExist()) {
            return $this->cardRepository->getAllUsersCards();
        }
        $this->cardRepository->addUserUuidToDb();
        $cardGenerateService->generateUserCards($this->cardRepository);
        return $this->cardRepository->getAllUsersCards();
    }
}