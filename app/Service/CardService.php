<?php

namespace App\Service;

use app\Repositories\CardRepository;
use app\Repositories\UserRepository;
use Illuminate\Http\Request;


class CardService
{
    public $userRepository;
    public $cardRepository;
    public $uuid;

    /**
     * CardService constructor.
     * @param Request $request
     * @param UserRepository $userRepository
     * @param CardRepository $cardRepository
     * @internal param CardRepository $cardRepository
     */
    public function __construct(Request $request, UserRepository $userRepository, CardRepository $cardRepository)
    {
        $this->userRepository = $userRepository;
        $this->cardRepository = $cardRepository;
        $this->uuid = $request->header('uuid');
    }

    /**
     * Вокврат карточек если UUID существует в базе данных
     * иначе добавить UUID в базу и сгененировать случайное число карт
     * @param CardGenerateService $cardGenerateService
     * @return mixed
     */
    public function checkUserCards(CardGenerateService $cardGenerateService)
    {
        if (!$user = $this->userRepository->findOneBy('uuid', $this->uuid)) {
            $user = $this->userRepository->create(['uuid' => $this->uuid]);
            $cardGenerateService->generateUserCards($user);
        }
        return $this->cardRepository->findAllBy('user_id', $user->id);
    }
}