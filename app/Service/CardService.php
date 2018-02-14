<?php

namespace App\Service;

use App\Card;
use app\Repositories\CardInterface;
use app\Repositories\UserInterface;
use Illuminate\Support\Facades\Storage;

class CardService
{
    private $userRepository;
    private $cardRepository;
    private $cardGenerateService;

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
        CardGenerateService $cardGenerateService,
        Card $card
    )
    {
        $this->userRepository = $userRepository;
        $this->cardRepository = $cardRepository;
        $this->cardGenerateService = $cardGenerateService;
        $this->card = $card;
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
        }

        foreach ($cards = $this->cardRepository->findAllWithEagerLoading('user_id', $user->id, ['frontPhoto', 'backPhoto'])
                 as $card) {
            $card->front_photo = Storage::url('storage/' . $card->frontPhoto->filename);
            $card->back_photo = Storage::url('storage/' . $card->backPhoto->filename);
        }
        return $cards;
    }
}