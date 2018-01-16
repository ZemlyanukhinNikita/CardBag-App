<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 16.01.18
 * Time: 18:30
 */

namespace App\Service;

use App\Card;

class CardGenerateService
{
    public function addUserCards()
    {
        factory(Card::class, random_int(1, 10))->create();
    }
}