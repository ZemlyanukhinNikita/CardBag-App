<?php

namespace App\Service;

use App\Card;
use Repositories\CardRepository;


class CardGenerateService
{
    /**
     * Генерация случайного количества карточек пользователю
     * @param CardRepository $cardRepository
     * @return mixed
     */
    public function generateUserCards(CardRepository $cardRepository)
    {
        $id = $cardRepository->getUsersId();
        return factory(Card::class, random_int(0, 10))->create([
            'user_id' => $id,
        ]);
    }
}