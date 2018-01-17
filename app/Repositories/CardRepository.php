<?php

namespace app\Repositories;


use App\Card;

class CardRepository extends EloquentRepository implements CardInterface
{

    public function getModel()
    {
        return new Card();
    }
}