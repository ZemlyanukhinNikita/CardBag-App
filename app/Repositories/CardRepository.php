<?php

namespace app\Repositories;

use App\Card;
use App\User;

class CardRepository extends EloquentRepository implements CardInterface
{

    /**
     * Метод возвращения модели Card
     * @return Card
     */
    public function getModel()
    {
        return new Card();
    }

    public function getAllUsersCards()
    {
        $id = $this->user->select('id')->where('uuid', $this->request->header('uuid'))->first();
        return $this->card->all()->where('user_id', $id->id);
    }

    public function getUsersId()
    {
        $id = $this->user->select('id')->where('uuid', $this->request->header('uuid'))->first();
        return $id->id;
    }

    public function getUserUuid()
    {
        return $this->user->select('uuid')->where('uuid', $this->request->header('uuid'))->first();
    }

    public function addUserUuidToDb()
    {
        $user = new User();
        $user->fill(array(
            'uuid' => $this->request->header('uuid')
        ));
        $user->save();
    }
}