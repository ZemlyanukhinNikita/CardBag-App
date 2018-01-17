<?php

namespace app\Repositories;


use App\Card;
use App\User;
use Illuminate\Http\Request;

class UserRepository extends EloquentRepository implements UserInterface
{
    private $card;
    private $request;

    /**
     * UserRepository constructor.
     * @param Request $request
     * @param Card $card
     */
    public function __construct(Request $request, Card $card)
    {
        $this->card = $card;
        $this->request = $request;
    }

    public function getAllCards(User $user)
    {
        $id = $user->select('id')->where('uuid', $this->request->header('uuid'))->first();
        return $this->card->all()->where('user_id', $id->id);
    }

    public function getUserUuid()
    {
        $this->findOneBy('uuid', $this->request->header('uuid'));
    }

    public function addUuidToDataBase()
    {
        $this->create(['uuid' => $this->request->header('uuid')]);
    }

    public function getId()
    {
        $this->findOneByTwoArguments('id', 'uuid', $this->request->header('uuid'));
    }

    public function getModel()
    {
        return new User();
    }
}