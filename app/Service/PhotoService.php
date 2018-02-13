<?php

namespace App\Service;

class PhotoService
{
    /**
     * Удаление фото с сервера
     * @param $photo
     */
    public function removingPhotoFromServer($photo)
    {
//        $this->checkingSendPhotoOnServer($photo);
//        unlink('storage/' . basename($photo));
    }

    /**
     * Проверка имеется ли присланное фото на сервере
     * @param $photo
     * @return bool
     */
    public function checkingSendPhotoOnServer($photo)
    {
        if (!file_exists('storage/' . basename($photo))) {
            abort(400, 'photo not found on server');
        }
    }
}