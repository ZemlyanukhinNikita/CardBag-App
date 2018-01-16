<?php
/**
 * Created by PhpStorm.
 * User: user
 * Date: 15.01.18
 * Time: 19:40
 */

namespace Repository;


interface RepositoryInterface
{
    public function getUserUuid();

    public function getAllUsersCards();
}