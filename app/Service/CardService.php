<?php

namespace App\Service;

use app\Repositories\CardInterface;
use App\Repositories\TokenInterface;
use app\Repositories\UserInterface;
use Illuminate\Support\Facades\Storage;

class CardService
{
    private $userRepository;
    private $cardRepository;
    private $tokenRepository;

    /**
     * CardService constructor.
     * @param UserInterface $userRepository
     * @param CardInterface $cardRepository
     * @param TokenInterface $tokenRepository
     * @internal param CardGenerateService $cardGenerateService
     * @internal param CardInterface $cardInterface
     * @internal param CardRepository $cardRepository
     */
    public function __construct(
        UserInterface $userRepository,
        CardInterface $cardRepository,
        TokenInterface $tokenRepository
    )
    {
        $this->userRepository = $userRepository;
        $this->cardRepository = $cardRepository;
        $this->tokenRepository = $tokenRepository;

    }

    /**
     * Вокврат карточек если UUID существует в базе данных
     * иначе добавить UUID в базу и сгененировать случайное число карт
     * @param $token
     * @return mixed
     */
    public
    function getUserCards($token)
    {

        $user = $this->userRepository->findOneBy('token', $this->tokenRepository->findOneBy('token', $token)->id);

        foreach ($cards = $this->cardRepository->findAllWithEagerLoading('user_id', $user->id, ['frontPhoto', 'backPhoto'])
                 as $card) {
            $card->front_photo = Storage::url('storage/' . $card->frontPhoto->filename);
            $card->back_photo = Storage::url('storage/' . $card->backPhoto->filename);
        }
        return $cards;
    }
}