<?php

namespace Repositories;


interface RepositoryInterface
{
    public function getUserUuid();

    public function getAllUsersCards();

    public function addUserUuidToDb();
}