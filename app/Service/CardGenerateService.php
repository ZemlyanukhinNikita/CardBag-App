<?php

namespace App\Service;

use App\Card;
use App\User;
use Repositories\CardRepository;

/**
 * Если UUID который пришел в header неизвестен, то он добавляется в базу данных
 * и вызывается метод generateUserCards который описан в данном классе.
 * Этот метод генерирует случайное количество карточек от 0-10 штук этому пользователю.
 * Пока что это временное решение.
 */
class CardGenerateService
{
    /**
     * CardGenerateService constructor.
     */
    public function __construct()
    {
    }

    /**
     * Генерация случайного количества карточек пользователю
     * @param User $user
     * @return mixed
     */
    public function generateUserCards(User $user)
    {
        return factory(Card::class, random_int(0, 10))->create([
            'user_id' => $user->id,
        ]);
    }
}