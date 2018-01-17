<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.01.18
 * Time: 19:43
 */

namespace Repositories;


use App\Card;
use App\User;
use Illuminate\Http\Request;

class CardRepository implements RepositoryInterface
{
    private $card;
    private $request;
    private $user;

    public function __construct(Request $request, User $user, Card $card)
    {
        $this->request = $request;
        $this->card = $card;
        $this->user = $user;
    }

    public function getAllUsersCards()
    {
        $id = $this->user->select('id')->where('uuid', $this->request->header('uuid'))->first();
        return $this->card->all()->where('user_id', $id->id);
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