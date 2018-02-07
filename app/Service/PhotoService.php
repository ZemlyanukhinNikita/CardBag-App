<?php

namespace App\Service;

class PhotoService
{
    /**
     * Удаление фото с сервера
     * @param $Photo
     */
    public function removingPhotosFromServer($Photo)
    {
        if (!file_exists('storage/' . $Photo)) {
            abort(400, 'photo not found');
        }
        unlink('storage/' . $Photo);
    }

    /**
     * Проверка имеется ли присланное фото на сервере
     * @param $front_photo
     * @param $back_photo
     */
    public function checkingSendPhotos($front_photo, $back_photo)
    {
        if (!(file_exists('storage/' . basename($front_photo)) &&
            file_exists('storage/' . basename($back_photo))
        )
        ) {
            abort(400, 'photo not found on server');
        }
    }
}