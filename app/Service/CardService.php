<?php

namespace App\Service;

use app\Repositories\CardInterface;
use App\Repositories\TokenInterface;
use app\Repositories\UserInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CardService
{
    private $userRepository;
    private $cardRepository;
    private $tokenRepository;
    private $request;

    /**
     * CardService constructor.
     * @param UserInterface $userRepository
     * @param CardInterface $cardRepository
     * @param TokenInterface $tokenRepository
     * @param Request $request
     * @internal param CardGenerateService $cardGenerateService
     * @internal param CardInterface $cardInterface
     * @internal param CardRepository $cardRepository
     */
    public function __construct(
        UserInterface $userRepository,
        CardInterface $cardRepository,
        TokenInterface $tokenRepository,
        Request $request
    )
    {
        $this->userRepository = $userRepository;
        $this->cardRepository = $cardRepository;
        $this->tokenRepository = $tokenRepository;
        $this->request = $request;
    }

    /**
     * Вокврат карточек если UUID существует в базе данных
     * иначе добавить UUID в базу и сгененировать случайное число карт
     * @return mixed
     */
    public function getUserCards()
    {
        foreach ($cards = $this->cardRepository->findAllWithEagerLoading('user_id', $this->request->user()->id,
            ['frontPhoto', 'backPhoto'])
                 as $card) {
            $card->front_photo = Storage::url('storage/' . $card->frontPhoto->filename);
            $card->back_photo = Storage::url('storage/' . $card->backPhoto->filename);
        }
        return $cards;
    }
}