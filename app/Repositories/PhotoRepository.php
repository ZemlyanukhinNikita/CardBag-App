<?php

namespace app\Repositories;

use App\Photo;

class PhotoRepository extends EloquentRepository implements PhotoInterface
{

    /**
     * {@inheritDoc}
     * Абстрактный метод получения модели, реализуется в дочерних классах
     * @return mixed
     */
    public function getModel()
    {
        return new Photo();
    }
}