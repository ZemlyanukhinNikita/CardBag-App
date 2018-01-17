<?php

namespace App\Service;

use app\Repositories\CardRepository;
use app\Repositories\UserRepository;
use Illuminate\Http\Request;


class CardService
{
    public $userRepository;
    public $cardRepository;
    public $request;
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
        $this->request = $request;
        $this->uuid = $request->header('uuid');
    }


    /**
     * Вокврат карточек если UUID существует в базе данных
     * иначе добавить UUID в базу и сгененировать случайное число карт
     * @param CardGenerateService $cardGenerateService
     * @param UserRepository $userRepository
     * @return static
     */
    public function checkUserCards(CardGenerateService $cardGenerateService)
    {
        if ($this->userRepository->findOneBy('uuid', $this->uuid)) {
            return $this->cardRepository->findAllBy('user_id',
                $this->userRepository->findOneBy('uuid', $this->uuid)->id);
        } else {
            $this->userRepository->create(['uuid' => $this->uuid]);
            $cardGenerateService->generateUserCards($this->userRepository);
            return $this->cardRepository->findAllBy('user_id',
                $this->userRepository->findOneBy('uuid', $this->uuid)->id);
        }
    }
}