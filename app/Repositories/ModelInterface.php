<?php

namespace app\Repositories;


interface ModelInterface
{

    /**
     * Метод сохранения модели в базу данных
     * @param array $values
     * @return Model
     */
    public function create(array $values): Model;

    /**
     * Метод получения всех значений таблицы, где $field == $value
     * @param string $field
     * @param $value
     * @return mixed
     */
    public function findAllBy(string $field, $value);

    /**
     * Метод получения значения $field из таблицы, где $field == $value
     * @param string $field
     * @param $value
     * @return mixed
     */
    public function findOneBy(string $field, $value);

    /**
     * Метод получения значения $field из таблицы, где $value == $arg
     * @param string $field
     * @param $value
     * @param $arg
     * @return mixed
     */
    public function findOneByTwoArguments(string $field, $value, $arg);

    /**
     * Метод получения всех значений таблицы
     * @return mixed
     */
    public function findAll();

}