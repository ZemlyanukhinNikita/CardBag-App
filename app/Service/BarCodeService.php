<?php

namespace app\Service;

use Exception;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Milon\Barcode\DNS1D;
use RobbieP\ZbarQrdecoder\ZbarDecoder;

class BarCodeService
{
    private $decoder;
    private $barCode;

    /**
     * BarCodeService constructor.
     * @param ZbarDecoder $decoder
     * @param DNS1D $barCode
     */
    public function __construct(ZbarDecoder $decoder, DNS1D $barCode)
    {
        $this->decoder = $decoder;
        $this->barCode = $barCode;
    }

    /**
     * Метод сканирования фотографии, возвращает код изображения
     * @param $fileName
     * @return mixed
     */
    public function scanBarCode($fileName)
    {
        try {
            return $this->decoder->make('storage/' . $fileName);
        } catch (Exception $e) {
            Log::error('Scanning image error: ' . $e->getMessage() . ' ' . $e->getCode());
        }
    }

    /**
     * Метод генерации изображения штрихкода
     * @param $code
     * @param $type
     * @return mixed
     */
    public function generateBarCodeImage($code, $type)
    {
        try {
            return $this->barCode->getBarcodePNGPath($code, $this->setBarcode($type));
        } catch (Exception $e) {
            Log::error('Generating image error: ' . $e->getMessage() . ' ' . $e->getCode());
        }
    }

    /**
     * Метод возвращает url картинки если она распозанана,
     * иначе null
     * @param $photo
     * @return url image or null
     */
    public function getImageUrlOrNull($photo)
    {
        if ($this->scanBarCode($photo)->code !== 400) {
            return Storage::url($this->generateBarCodeImage($this->scanBarCode($photo)->text,
                $this->scanBarCode($photo)->format));
        }
        return null;
    }

    /**
     * Для создания картинки штрихкода нужно передать тип штрихкода, считанный с фотографии карточки,
     * считанный тип и тип штрихкода нужный для создания картинки отличаются, т.к используется два разных сервиса.
     * Метод устанавливает нужный тип штрихкода для генерации картинок
     * @param $format
     * @return bool|string
     */
    private function setBarcode($format)
    {
        switch ($format) {
            case 'CODE_39': {
                return 'C39';
            }
            case 'INTERLEAVED_2_5': {
                return 'I25';
            }
            case 'CODE_128': {
                return 'C128';
            }
            case 'EAN_13': {
                return 'EAN13';
            }
            case 'QR_CODE': {
                return 'QRCODE';
            }
            default: {
                return false;
            }
        }
    }
}