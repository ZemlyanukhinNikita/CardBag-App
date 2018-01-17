<?php

namespace app\Repositories;

use App\Card;

class CardRepository extends EloquentRepository implements CardInterface
{
    /**
     * {@inheritDoc}
     * Метод возвращения модели Card
     * @return Card
     */
    public function getModel()
    {
        return new Card();
    }
}