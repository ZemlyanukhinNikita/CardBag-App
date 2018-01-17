<?php

namespace app\Repositories;


use Illuminate\Database\Eloquent\Model;

interface ModelInterface
{

    /**
     * Метод сохранения модели в базу данных
     * @param array $values
     * @return Model
     */
    public function create(array $values): Model;

    /**
     * Метод получения моеделей, где $field == $value
     * @param string $field
     * @param $value
     * @return mixed
     */
    public function findAllBy(string $field, $value);

    /**
     * Метод получения модели, где $field == $value
     * @param string $field
     * @param $value
     * @return mixed
     */
    public function findOneBy(string $field, $value);

    /**
     * Метод получения всех моделей
     * @return mixed
     */
    public function findAll();

}