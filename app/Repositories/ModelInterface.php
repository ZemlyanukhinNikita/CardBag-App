<?php

namespace app\Repositories;


use Illuminate\Database\Eloquent\Model;

interface ModelInterface
{
    /**
     * Метод сохранения модели в базу данных, реализуется в дочерних классах
     * @param array $values
     * @return Model
     */
    public function create(array $values): Model;

    /**
     * Метод получения моделей, где $field == $value, реализуется в дочерних классах
     * @param string $field
     * @param $value
     * @return mixed
     */
    public function findAllBy(string $field, $value);

    /**
     * Метод получения всех моделей, вместе с удаленными, реализуется в дочерних классах
     * @param string $field
     * @param $value
     * @return mixed
     */
    public function findAllWithTrashedBy(string $field, $value);

    /**
     * Метод получения модели, где $field == $value, реализуется в дочерних классах
     * @param string $field
     * @param $value
     * @return mixed
     */
    public function findOneBy(string $field, $value);

    public function findOneByWithTrashedBy(string $field, $value);

    /**
     * Метод получения всех моделей, реализуется в дочерних классах
     * @return mixed
     */
    public function findAll();

    /**
     * Метод получения всех моделей отсортированных по алфавиту, реализуется в дочерних классах
     * @param String $field
     * @param String $value
     * @return mixed
     */
    public function findAllOrderBy(String $field);

    /**
     * Метод удаления модели из базы данных, реализуется в дочерних классах
     * @param string $field
     * @param string $value
     * @return mixed
     */
    public function delete(string $field, string $value);

    /**
     * Метод редактирования модели в базе данных, реализуется в дочерних классах
     * @param string $field
     * @param string $value
     * @param array $values
     * @return mixed
     */
    public function update(string $field, string $value, array $values);
}