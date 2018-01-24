<?php
namespace app\Repositories;

use App\Category;

class CategoryRepository extends EloquentRepository implements CategoryInterface
{
    /**
     * {@inheritDoc}
     * Абстрактный метод получения модели, реализуется в дочерних классах
     * @return mixed
     */
    public function getModel()
    {
        return new Category();
    }
}