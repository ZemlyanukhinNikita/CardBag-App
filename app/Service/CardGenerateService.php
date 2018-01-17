<?php

namespace App\Service;

use App\Card;
use App\User;
use Illuminate\Http\Request;
use Repositories\CardRepository;


class CardGenerateService
{
    public $request;
    public $uuid;

    /**
     * CardGenerateService constructor.
     * @param Request $request
     */
    public function __construct(Request $request)
    {
        $this->request = $request;
        $this->uuid = $request->header('uuid');
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