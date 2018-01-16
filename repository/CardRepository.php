<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.01.18
 * Time: 19:43
 */

namespace Repository;


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
        return $this->card->join('users', function ($join) {
            $join->on('users.id', 'cards.user_id')->where('users.uuid',
                $this->request->header('uuid'));
        })->get();
    }

    public function getUserUuid()
    {
        return $this->user->select('uuid')->where('uuid', $this->request->header('uuid'))->first();
    }
}