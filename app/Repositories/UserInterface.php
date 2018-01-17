<?php

namespace app\Repositories;


use App\User;

interface UserInterface extends ModelInterface
{
    public function getAllCards(User $user);
}