<?php

namespace App\Service;

use app\Repositories\PhotoInterface;
use Illuminate\Http\Request;

class PhotoService
{
    private $request;
    private $photoRepository;

    /**
     * PhotoService constructor.
     * @param $request
     * @param $photoRepository
     */
    public function __construct(Request $request, PhotoInterface $photoRepository)
    {
        $this->request = $request;
        $this->photoRepository = $photoRepository;
    }


    /**
     * Удаление фото с сервера
     * @param $photo
     */
    public function removingPhotoFromServer($photo)
    {
        $photos = $this->checkingSendPhotoOnServer($photo);
        unlink('storage/' . $photos->filename);
        $this->photoRepository->delete('filename', $photos->filename);
    }

    /**
     * Проверка имеется ли присланное фото на сервере
     * @param $photo
     * @return mixed
     */
    public function checkingSendPhotoOnServer($photo)
    {
        $photos = $this->photoRepository->findOneBy('filename', basename($photo));

        if (!$photos) {
            abort(400, 'Photo not found on server');
        }

        if ($photos->user_id !== $this->request->user()->id) {
            abort(403, 'Permission denied');
        }

        return $photos;
    }
}