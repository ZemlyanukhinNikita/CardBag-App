<?php

namespace App\Service;

use app\Repositories\CardInterface;
use app\Repositories\UserInterface;

class CardService
{
    public $userInterface;
    public $cardInterface;
    public $cardGenerateService;

    /**
     * CardService constructor.
     * @param UserInterface $userRepository
     * @param CardInterface $cardRepository
     * @param CardGenerateService $cardGenerateService
     * @internal param CardInterface $cardInterface
     * @internal param CardRepository $cardRepository
     */
    public function __construct(
        UserInterface $userRepository,
        CardInterface $cardRepository,
        CardGenerateService $cardGenerateService
    )
    {
        $this->userInterface = $userRepository;
        $this->cardInterface = $cardRepository;
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
        if (!$user = $this->userInterface->findOneBy('uuid', $uuid)) {
            $user = $this->userInterface->create(['uuid' => $uuid]);
            $this->cardGenerateService->generateUserCards($user);
        }
        return $this->cardInterface->findAllBy('user_id', $user->id);
    }
}