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
        $this->checkingSendPhotoOnServer($photo);
        unlink('storage/' . basename($photo));
    }

    /**
     * Проверка имеется ли присланное фото на сервере
     * @param $photo
     * @return bool
     */
    public function checkingSendPhotoOnServer($photo)
    {
        if (!preg_match('/(https?:\\/\\/localhost\\/backend\\/public\\/storage\\/.*\.(?:png|jpg|gif|bmp|svg|jpeg))/i', $photo)) {
            abort(422, 'Not valid url image');
        }

//        if (!preg_match('/(https?:\\/\\/cardbag.ru\\/storage\\/.*\.(?:png|jpg|gif|bmp|svg|jpeg))/i', $photo)) {
//            abort(422, 'Not valid url image');
//        }

        if (!file_exists('storage/' . basename($photo))) {
            abort(400, 'photo not found on server');
        }
    }
}