<?php

namespace App\Service;

use app\Repositories\CardRepository;
use app\Repositories\UserRepository;

class CardService
{
    public $userRepository;
    public $cardRepository;
    public $cardGenerateService;

    /**
     * CardService constructor.
     * @param UserRepository $userRepository
     * @param CardRepository $cardRepository
     * @param CardGenerateService $cardGenerateService
     * @internal param CardRepository $cardRepository
     */
    public function __construct(
        UserRepository $userRepository,
        CardRepository $cardRepository,
        CardGenerateService $cardGenerateService
    )
    {
        $this->userRepository = $userRepository;
        $this->cardRepository = $cardRepository;
        $this->cardGenerateService = $cardGenerateService;
    }

    /**
     * Вокврат карточек если UUID существует в базе данных
     * иначе добавить UUID в базу и сгененировать случайное число карт
     * @param $uuid
     * @return mixed
     */
    public function getUserCards($uuid)
    {
        if (!$user = $this->userRepository->findOneBy('uuid', $uuid)) {
            $user = $this->userRepository->create(['uuid' => $uuid]);
            $this->cardGenerateService->generateUserCards($user);
        }
        return $this->cardRepository->findAllBy('user_id', $user->id);
    }
}