<?php

namespace App\Service;

use app\Repositories\CardInterface;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class CardService
{
    private $cardRepository;
    private $request;

    /**
     * CardService constructor.
     * @param CardInterface $cardRepository
     * @param Request $request
     * @internal param CardGenerateService $cardGenerateService
     * @internal param CardInterface $cardInterface
     * @internal param CardRepository $cardRepository
     */
    public function __construct(
        CardInterface $cardRepository,
        Request $request
    )
    {
        $this->cardRepository = $cardRepository;
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