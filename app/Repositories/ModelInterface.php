<?php

namespace app\Repositories;


interface ModelInterface
{
    public function create(array $values): Model;

    public function findAllBy(string $field, $value);

    public function findOneBy(string $field, $value);

    public function findOneByTwoArguments(string $field, $value, $arg);

    public function findAll();

}